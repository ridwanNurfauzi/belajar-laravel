<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Yajra\DataTables\Html\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class AuthorsController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $authors = Author::all();
            return DataTables::of($authors)
                ->addColumn('action', function ($author) {
                    return view('datatable._action', [
                        'model' => $author,
                        'form_url' => route('authors.destroy', $author->id),
                        'edit_url' => route('authors.edit', $author->id),
                        'confirm_message' => 'Apakah anda yakin ingin menghapus '.$author->name.'?'
                    ]);
                })
                ->make(true);
        }
        $html = $htmlBuilder
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);

        return view('authors.index')->with(compact('html'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required|unique:authors']);
        $author = Author::create($request->all());
        Session::flash("flash_notification", [
            "level" => 'success',
            'message' => 'Berhasil menyimpan data ' . $author->name
        ]);
        return redirect()->route('authors.index');
    }

    public function show(string $id)
    {
        // Menampilkan item berdasarkan id
    }

    public function edit(string $id)
    {
        $author = Author::find($id);
        return view('authors.edit')->with(compact('author'));
    }

    public function update(Request $request, string $id)
    {
        $this->validate($request, ['name' => 'required|unique:authors,name,' . $id]);
        $author = Author::find($id);
        $author->update($request->only('name'));
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan $author->name"
        ]);
        return redirect()->route('authors.index');
    }

    public function destroy(string $id)
    {
        if (!Author::destroy($id)){
            return redirect()->back();
        }
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Penulis berhasil dihapus"
        ]);
        return redirect()->route('authors.index');
    }
}
