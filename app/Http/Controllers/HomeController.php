<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\RoleUser;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    protected function adminDashboard()
    {
        $authors = [];
        $books = [];
        foreach (Author::all() as $author) {
            array_push($authors, $author->name);
            array_push($books, $author->books->count());
        }
        return view('dashboard.admin', compact('authors', 'books'));
        // return view('dashboard.admin');
    }
    protected function memberDashboard()
    {
        // return view('dashboard.member');
        $borrowLogs = Auth::user()->borrowLogs()->borrowed()->get();
        return view('dashboard.member', compact('borrowLogs'));
    }

    public function index()
    {
        if (RoleUser::hasRole('admin'))
            return $this->adminDashboard();
        if (RoleUser::hasRole('member'))
            return $this->memberDashboard();

        return view('home');
    }
}
