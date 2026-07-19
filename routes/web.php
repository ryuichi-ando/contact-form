<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TagController;

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
/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', [ContactController::class, 'index']);
Route::post('contacts/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::post('contacts', [ContactController::class, 'store'])->name('contact.store');
Route::post('contacts/back', [ContactController::class, 'back'])->name('contact.back');
Route::get('contacts/thanks', function () {
    return view('contact.thanks');
})->name('contact.thanks');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/admin/contacts/{contact}', [AdminController::class, 'show'])->name('admin.show');

    Route::post('/admin/tags', [TagController::class, 'store'])->name('admin.tags.store');

    Route::get('/admin/tags/{tag}/edit', [TagController::class, 'edit'])->name('admin.tags.edit');

    Route::put('/admin/tags/{tag}', [TagController::class, 'update'])->name('admin.tags.update');

    Route::delete('/admin/tags/{tag}', [TagController::class, 'destroy'])->name('admin.tags.destroy');

    Route::delete('/admin/contacts/{contact}', [AdminController::class, 'destroy'])->name('admin.destroy');
});