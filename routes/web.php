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
Route::post('/contacts/update-sample', [App\Http\Controllers\ClientController::class, 'updateSample']);


Route::get('/pipeline', [App\Http\Controllers\PipelineController::class, 'index'])->name('pipeline');
Route::post('/pipeline/update', [App\Http\Controllers\PipelineController::class, 'update'])->name('pipeline.update');
Route::get('/pipeline/{id}/getdata', [App\Http\Controllers\PipelineController::class, 'getdata'])->name('pipeline.getdata');
Route::post('/pipeline/contact/edit', [App\Http\Controllers\PipelineController::class, 'edit'])->name('pipeline.contact.edit');
Route::post('/pipeline/update-status', [App\Http\Controllers\PipelineController::class, 'updateStatus'])->name('pipeline.updateStatus');
Route::post('/pipeline/send-email', [App\Http\Controllers\PipelineController::class, 'sendEmail'])->name('pipeline.sendEmail');
Route::post('/pipeline/update-date', [App\Http\Controllers\PipelineController::class, 'updateDate'])->name('pipeline.updateDate');
Route::post('/pipeline/send-whatsapp', [App\Http\Controllers\PipelineController::class, 'sendWhatsapp'])->name('pipeline.send.whatsapp');
Route::post('/pipeline/contact/{id}/edit-inline', [App\Http\Controllers\PipelineController::class, 'editInline']);

Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
Route::post('/users/save', [App\Http\Controllers\UserController::class, 'save']);
Route::post('/users/add', [App\Http\Controllers\UserController::class, 'add']);
Route::post('/users/delete', [App\Http\Controllers\UserController::class, 'delete']);

