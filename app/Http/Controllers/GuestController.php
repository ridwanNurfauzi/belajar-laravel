<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowLog;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class GuestController extends Controller
{
    //
    public function index(Request $request, Builder $htmlBuilder) {
        if($request->ajax()) {
            $books = Book::with('author');
            return DataTables::of($books)
                ->addColumn('action', function ($book) {
                        return '<a class="btn btn-primary" href="'.
                        route('guest.books.borrow', $book->id)
                        .'">Pinjam</a>';
                    })->make(true);
        }
        $html = $htmlBuilder
            ->addColumn(['data' => 'title', 'name' => 'title', 'title' => 'Judul'])
            ->addColumn(['data' => 'author.name', 'name' => 'author.name', 'title' => 'Penulis'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'orderable' => false, 'title' => '',
            'searchable' => false
        ]);

        return view('guest.index')->with(compact('html'));
    }
}
