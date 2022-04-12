<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingsController;
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
Route::get('admin/invoices/edit/{invoice}', [AdminController::class, 'edit'])->name('admin.invoices.edit')->middleware('admin');
Route::get('admin/settings', [SettingsController::class, 'index'])->name('admin.settings')->middleware('admin');
Route::post('admin/settings', [SettingsController::class, 'store'])->name('store.settings')->middleware('admin');
Route::get('admin/activities', [SettingsController::class, 'activities'])->name('store.activities')->middleware('admin');
Route::get('invoices/{invoice}', [AdminController::class, 'invoice'])->name('admin.invoices.view')->middleware('admin');
Route::get('invoices/res/{invoice}', [AdminController::class, 'response'])->name('admin.invoices.res')->middleware('admin');
Route::get('invoices/active/{invoice}', [AdminController::class, 'active'])->name('admin.invoices.cancel')->middleware('admin');
Route::post('admin/store', [AdminController::class, 'store'])->name('admin.store')->middleware('admin');
Route::post('admin/edit/store', [AdminController::class, 'storeedit'])->name('admin.edit.store')->middleware('admin');

require __DIR__.'/auth.php';
