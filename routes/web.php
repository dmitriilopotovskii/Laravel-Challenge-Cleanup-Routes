<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\UserSettingsController;
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

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');


Route::middleware(['auth'])->group(function () {

    Route::name('books.')->group(function () {

        Route::get('book/{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('book/{book}/report', [BookReportController::class, 'store'])->name('report.store');
    });
    Route::get('book/create', [BookController::class, 'create'])->name('books.create');
    Route::post('book/store', [BookController::class, 'store'])->name('books.store');

    Route::prefix('user')->name('user.')->group(function () {

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
        Route::resource('books', BookController::class)->only('destroy', 'index', 'edit', 'update')
            ->names(['index' => 'books.list'])
            ->scoped(['book' => 'slug']);

    });
});
Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::middleware(['isAdmin'])->name('admin.')->group(function () {

    Route::get('admin', AdminDashboardController::class)->name('index');
    Route::resource('users', AdminUsersController::class)->only('edit', 'update', 'destroy', 'index');
    Route::resource('books', AdminBookController::class);
    Route::put('admin/book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');

});


require __DIR__ . '/auth.php';
