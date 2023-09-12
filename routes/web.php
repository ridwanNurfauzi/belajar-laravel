<?php

use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\HomeController;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix'=>'admin'], function(){
// Route::group(['prefix'=>'admin', 'middleware' => ['auth']], function(){
// Route::group(['prefix'=>'admin', 'middleware' => ['auth', 'role:admin']], function(){
    // Route
    Route::resource('authors', AuthorsController::class);
});
