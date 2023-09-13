<?php

use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\HomeController;
use App\Models\Role;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laratrust\Laratrust;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('admin')->middleware([Authenticate::class])->group(function(){
// Route::group(['prefix'=>'admin', 'middleware' => ['auth']], function(){
// Route::group(['prefix'=>'admin', 'middleware' => ['auth', 'role:admin']], function(){
    // Route
    Route::resource('authors', AuthorsController::class);
    Route::resource('books', BooksController::class);
});
