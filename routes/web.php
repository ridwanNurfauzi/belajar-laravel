<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StatisticsController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [GuestController::class, 'index']);

Route::get('/books/{book}/borrow', [BooksController::class, 'borrow'])->name('guest.books.borrow');


Route::put('/books/{book}/return', [BooksController::class, 'returnBack'])->name('member.books.return');


Route::get('auth/verify/{token}', [RegisterController::class, 'verify']);
Route::get('auth/send-verification', [RegisterController::class, 'sendVerification']);

Route::get('settings/profile', [SettingsController::class, 'profile']);
Route::get('settings/profile/edit', [SettingsController::class, 'editProfile']);
Route::post('settings/profile', [SettingsController::class, 'updateProfile']);

Route::get('settings/password', [SettingsController::class, 'editPassword']);
Route::post('settings/password', [SettingsController::class, 'updatePassword']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('admin')->middleware([Authenticate::class])->group(function(){
// Route::group(['prefix'=>'admin', 'middleware' => ['auth']], function(){
// Route::group(['prefix'=>'admin', 'middleware' => ['auth', 'role:admin']], function(){
    // Route
    Route::resource('authors', AuthorsController::class);
    Route::resource('books', BooksController::class);
    Route::resource('members', MembersController::class);
    Route::get('statistics', [StatisticsController::class, 'index'])->name('statistics.index');
});
