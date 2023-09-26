<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class GuestController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $books = Book::with('author');
            return DataTables::of($books)
                ->addColumn('stock', function ($book) {
                    return $book->stock;
                })
                ->addColumn('action', function ($book) {
                    if (RoleUser::hasRole('admin'))
                        return '';
                    else
                        return '<a class="btn btn-primary" href="' .
                            route('guest.books.borrow', $book->id)
                            . '">Pinjam</a>';
                })->make(true);
        }
        $html = $htmlBuilder
            ->addColumn(['data' => 'title', 'name' => 'title', 'title' => 'Judul'])
            ->addColumn(['data' => 'author.name', 'name' => 'author.name', 'title' => 'Penulis'])
            ->addColumn([
                'data' => 'stock', 'name' => 'stock', 'orderable' => false, 'title' => 'Stok',
                'searchable' => false
            ])
            ->addColumn([
                'data' => 'action', 'name' => 'action', 'orderable' => false, 'title' => '',
                'searchable' => false
            ]);

        return view('guest.index')->with(compact('html'));
    }
}
