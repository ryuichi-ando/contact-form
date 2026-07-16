<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;

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
});