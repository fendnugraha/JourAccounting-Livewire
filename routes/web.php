<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\JournalController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::group(['middleware' => ['auth']], function () {
    Route::view('settings', 'settings.settings')->name('settings');

    Route::view('settings/user', 'settings.user.index', ['title' => 'User Management'])->name('settings.user.index');

    //COA
    Route::get('settings/account', [ChartOfAccountController::class, 'index'])->name('settings.account.index');

    //Warehouse
    Route::get('settings/warehouse', [WarehouseController::class, 'index'])->name('settings.warehouse.index');
    Route::get('/setting/warehouse/{warehouse}/edit', [WarehouseController::class, 'edit']);
    Route::put('/setting/warehouse/{warehouse}/edit', [WarehouseController::class, 'update'])->name('warehouse.update');

    //Product
    Route::get('settings/product', [ProductController::class, 'index'])->name('settings.product.index');

    //Contact
    Route::get('settings/contact', [ContactController::class, 'index'])->name('settings.contact.index');

    //Journal
    Route::get('settings/transaction', [JournalController::class, 'index'])->name('transaction');
    Route::get('/journal/{id}/edit', [JournalController::class, 'edit'])->name('journal.edit');
    Route::put('/journal/{id}/edit', [JournalController::class, 'update'])->name('journal.update');
});

require __DIR__ . '/auth.php';
