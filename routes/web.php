<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/contacts', [App\Http\Controllers\ClientController::class, 'index'])->name('contacts');

Route::post('/contacts/get_list', [App\Http\Controllers\ClientController::class, 'get_list']);
Route::post('/contacts/delete', [App\Http\Controllers\ClientController::class, 'deleteContact']);
Route::post('/contacts/new', [App\Http\Controllers\ClientController::class, 'newContact']);
Route::post('/contacts/edit', [App\Http\Controllers\ClientController::class, 'edit']);
Route::post('/contacts/editcolumn', [App\Http\Controllers\ClientController::class, 'editcolumn']);
Route::get('/contacts/whatsapp', [App\Http\Controllers\ClientController::class, 'whatsapp']);
Route::post('/contacts/check-phone', [App\Http\Controllers\ClientController::class, 'checkPhone']);
Route::post('/contacts/update-date', [App\Http\Controllers\ClientController::class, 'updateDate']);


Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
Route::post('/users/save', [App\Http\Controllers\UserController::class, 'save']);
Route::post('/users/add', [App\Http\Controllers\UserController::class, 'add']);
Route::post('/users/delete', [App\Http\Controllers\UserController::class, 'delete']);
