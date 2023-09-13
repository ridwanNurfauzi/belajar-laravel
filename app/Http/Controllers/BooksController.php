<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if($request->ajax()) {
            $books = Book::with('author');
            return DataTables::of($books)
                ->addColumn('action', function ($book) {
                    return view('datatable._action', [
                        'model' => $book,
                        'form_url' => route('books.destroy', $book->id),
                        'edit_url' => route('books.edit', $book->id),
                        'confirm_message' => 'Apakah Anda yakin ingin menghapus '.$book->title.'?'
                    ]);
                })->make(true);
        }
        $html = $htmlBuilder
            ->addColumn(['data' => 'title', 'name' => 'title', 'title' => 'Judul'])
            ->addColumn(['data' => 'amount', 'name' => 'amount', 'title' => 'Jumlah'])
            ->addColumn(['data' => 'author.name', 'name' => 'author.name', 'title' => 'Penulis'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'orderable' => false, 'title' => '',
            'searchable' => false
        ]);

        return view('books.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

            $filename = md5(time()).'.'.$exetension;

            $destinationPath = public_path(). DIRECTORY_SEPARATOR.'img';
            $uploaded_cover->move($destinationPath, $filename);

            $book->cover = $filename;
            $book->save();
        }
        else {
            Session::flash("flash_notification",[
                'level' => 'success',
                'message' => "Berhasil menyimpan $book->title tanpa cover"
            ]);
    
            return redirect()->route('books.index');
        }

        Session::flash("flash_notification",[
            'level' => 'success',
            'message' => "Berhasil menyimpan $book->title"
        ]);

        return redirect()->route('books.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::find($id);
        return view ('books.edit')->with(compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'title' => 'required|unique:books,title,'. $id,
            'author_id' => 'required|exists:authors,id',
            'amount' => 'required|numeric',
            'cover' => 'nullable|image|max:2048'
        ]);

        $book = Book::find($id);
        $book->update($request->all());

        if ($request->hasFile('cover')) {
            $uploaded_cover = $request->file('cover');

            $exetension = $uploaded_cover->getClientOriginalExtension();

            $filename = md5(time()).'.'.$exetension;

            $destinationPath = public_path(). DIRECTORY_SEPARATOR.'img';
            $uploaded_cover->move($destinationPath, $filename);

            if ($book->cover) {
                $old_cover =  $book->cover;
                $filepath = public_path(). DIRECTORY_SEPARATOR.'img'
                . DIRECTORY_SEPARATOR. $book->cover;

                try {
                    File::delete($filepath);
                }
                catch (FileNotFoundException $e){
                    
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);

        if ($book->cover){
            $old_cover = $book->cover;
            $filepath = public_path(). DIRECTORY_SEPARATOR. 'img'. 
            DIRECTORY_SEPARATOR. $book->cover;

            try {
                File::delete($filepath);
            }
            catch(FileNotFoundException $e){
                # error
            }
        }

        $book ->delete();

        Session::flash('flash_notification', [
            'level' => 'success',
            "message" => "Buku berhasil dihapus"
        ]);

        return redirect()->route('books.index');
    }
}
