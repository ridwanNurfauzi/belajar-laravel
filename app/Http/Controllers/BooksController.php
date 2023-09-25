<?php

namespace App\Http\Controllers;

use App\Exceptions\BookException;
use App\Exports\BooksExport;
use App\Exports\BooksTemplate;
use App\Imports\BooksImport;
use App\Models\Author;
use App\Models\Book;
use App\Models\BorrowLog;
use App\Models\RoleUser;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class BooksController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $books = Book::with('author');
            return DataTables::of($books)
                ->addColumn('action', function ($book) {
                    return view('datatable._action', [
                        'model' => $book,
                        'form_url' => route('books.destroy', $book->id),
                        'edit_url' => route('books.edit', $book->id),
                        'confirm_message' => 'Apakah Anda yakin ingin menghapus ' . $book->title . '?'
                    ]);
                })->make(true);
        }
        $html = $htmlBuilder
            ->addColumn(['data' => 'title', 'name' => 'title', 'title' => 'Judul'])
            ->addColumn(['data' => 'amount', 'name' => 'amount', 'title' => 'Jumlah'])
            ->addColumn(['data' => 'author.name', 'name' => 'author.name', 'title' => 'Penulis'])
            ->addColumn([
                'data' => 'action', 'name' => 'action', 'orderable' => false, 'title' => '',
                'searchable' => false
            ]);

        return view('books.index')->with(compact('html'));
    }

    public function create()
    {
        return view('books.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:books,title',
            'author_id' => 'required|exists:authors,id',
            'amount' => 'required|numeric',
            'cover' => 'nullable|image|max:2048'
        ]);

        $book = Book::create($request->except('cover'));

        if ($request->hasFile('cover')) {
            $uploaded_cover = $request->file('cover');

            $exetension = $uploaded_cover->getClientOriginalExtension();

            $filename = md5(time()) . '.' . $exetension;

            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
            $uploaded_cover->move($destinationPath, $filename);

            $book->cover = $filename;
            $book->save();
        } else {
            Session::flash("flash_notification", [
                'level' => 'success',
                'message' => "Berhasil menyimpan $book->title tanpa cover"
            ]);

            return redirect()->route('books.index');
        }

        Session::flash("flash_notification", [
            'level' => 'success',
            'message' => "Berhasil menyimpan $book->title"
        ]);

        return redirect()->route('books.index');
    }

    public function show(string $id)
    {
        // Menampilkan item berdasarkan id
    }

    public function edit(string $id)
    {
        $book = Book::find($id);
        return view('books.edit')->with(compact('book'));
    }

    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'title' => 'required|unique:books,title,' . $id,
            'author_id' => 'required|exists:authors,id',
            'amount' => 'required|numeric',
            'cover' => 'nullable|image|max:2048'
        ]);

        $book = Book::find($id);
        if (!$book->update($request->all()))
            return redirect()->back();

        if ($request->hasFile('cover')) {
            $uploaded_cover = $request->file('cover');

            $exetension = $uploaded_cover->getClientOriginalExtension();

            $filename = md5(time()) . '.' . $exetension;

            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
            $uploaded_cover->move($destinationPath, $filename);

            if ($book->cover) {
                $old_cover =  $book->cover;
                $filepath = public_path() . DIRECTORY_SEPARATOR . 'img'
                    . DIRECTORY_SEPARATOR . $book->cover;

                try {
                    File::delete($filepath);
                } catch (FileNotFoundException $e) {
                }
            }

            $book->cover = $filename;
            $book->save();
        }

        Session::flash("flash_notification", [
            'level' => 'success',
            "message" => "Berhasil menyimpan $book->title"
        ]);

        return redirect()->route('books.index');
    }

    public function destroy(Request $request, string $id)
    {
        $book = Book::find($id);
        $cover = $book->cover;
        if (!$book->delete())
            return redirect()->back();

        if ($request->ajax())
            return response()->json(['id' => $id]);

        if ($cover) {
            $old_cover = $book->cover;
            $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' .
                DIRECTORY_SEPARATOR . $book->cover;

            try {
                File::delete($filepath);
            } catch (FileNotFoundException $e) {
                # error
            }
        }

        $book->delete();

        Session::flash('flash_notification', [
            'level' => 'success',
            "message" => "Buku berhasil dihapus"
        ]);

        return redirect()->route('books.index');
    }

    public function borrow($id)
    {
        if (Auth::user() != null) {
            try {
                $book = Book::findOrFail($id);
                Auth::user()->borrow($book);

                Session::flash("flash_notification", [
                    "level" => "success",
                    "message" => "Berhasil meminjam $book->title"
                ]);
            } catch (BookException $e) {
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "message" => $e->getMessage()
                ]);
            } catch (ModelNotFoundException $e) {
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "message" => "Buku tidak ditemukan."
                ]);
            }
            return redirect('/');
        } else {
            Session::flash("flash_notification", [
                "level" => "warning",
                "message" => "Mohon login terlebih dahulu"
            ]);

            return redirect('/login');
        }
    }

    public function returnBack($book_id)
    {
        if (Auth::user() && RoleUser::hasRole('member')) {
            $borrowLog = BorrowLog::where('user_id', Auth::user()->id)
                ->where('book_id', $book_id)
                ->where('is_returned', 0)
                ->first();
            if ($borrowLog) {
                $borrowLog->is_returned = true;
                $borrowLog->save();
                Session::flash("flash_notification", [
                    "level" => "success",
                    "message" => "Berhasil mengembalikan " . $borrowLog->book->title
                ]);
            }
            return redirect('/home');
        } else {
            return redirect('/login');
        }
    }

    public function export()
    {
        return view('books.export');
    }
    public function exportPost(Request $request)
    {
        $this->validate($request, [
            'author_id' => 'required',
            'type' => 'required|in:pdf,xls'
        ], [
            'author_id.required' => 'Anda belum memilih penulis. Pilih minimal 1 penulis.'
        ]);
        $books = Book::whereIn('author_id', $request->get('author_id'))->get();
        if ($request->get('type') == 'xls')
            return Excel::download(new BooksExport($books), 'Data Buku Larapus.xls');
        else {
            if ($request->get('type') == 'pdf') {
                return $this->exportPdf($books);
            }
        }
    }

    private function exportPdf($books = null)
    {
        $pdf = Pdf::loadView('pdf.books', compact('books'));
        return $pdf->download('buku.pdf');
    }

    public function generateExcelTemplate()
    {
        return Excel::download(new BooksTemplate(), 'template-buku.xls');
    }
    public function importExcel(Request $request)
    {
        $this->validate($request, ['excel' => 'required|mimes:xls,xlsx']);
        $excel = $request->file('excel');
        $excels = Excel::toArray(new BooksImport(), $excel)[0];
        $rowRules = [
            'judul' => 'required',
            'penulis' => 'required',
            'jumlah' => 'required'
        ];
        $books_id = [];
        foreach ($excels as $row) {
            $validator = Validator::make($row, $rowRules);
            if ($validator->fails()) continue;
            $author = Author::where('name', $row['penulis'])->first();
            if (!$author) {
                $author = Author::create(['name' => $row['penulis']]);
            }
            $book = Book::create([
                'title' => $row['judul'],
                'author_id' => $author->id,
                'amount' => $row['jumlah']
            ]);
            array_push($books_id, $book->id);
        }


        $books = Book::whereIn('id', $books_id)->get();
        if ($books->count() == 0) {
            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Tidak ada buku yang berhasil diimport."
            ]);
            return redirect()->back();
        }
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil mengimport " . $books->count() . " buku."
        ]);

        return view('books.import-review')->with(compact('books'));
    }
}
