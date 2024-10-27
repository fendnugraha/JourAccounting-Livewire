<?php

use App\Models\Role;
use App\Livewire\User\UserTable;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ChartOfAccountController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/journal', [JournalController::class, 'index'])->name('journal');

    Route::get('/setting', fn() => view('setting.index', ['title' => 'Setting Page']))->name('setting');
    Route::get('/setting/account', [ChartOfAccountController::class, 'index'])->name('account.index');
    Route::get('/setting/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
    Route::get('/setting/user', UserTable::class)->name('user.index');

    Route::get('/setting/contact', [ContactController::class, 'index'])->name('contact.index');
});


require __DIR__ . '/auth.php';
