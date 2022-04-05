<?php

use App\Http\Controllers\AdminController;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::get('admin/home', [AdminController::class, 'index'])->name('admin.home')->middleware('admin');
Route::get('admin/invoices', [AdminController::class, 'invoices'])->name('admin.invoices')->middleware('admin');
Route::get('invoices/{invoice}', [AdminController::class, 'invoice'])->name('admin.invoices.view');
Route::post('admin/store', [AdminController::class, 'store'])->name('admin.store')->middleware('admin');

require __DIR__.'/auth.php';
