<?php

use App\Http\Controllers\ChartOfAccountController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::group(['middleware' => ['auth']], function () {
    Route::view('settings', 'settings.settings')->name('settings');

    //COA
    Route::get('settings/account', [ChartOfAccountController::class, 'index'])->name('settings.account.index');
});

require __DIR__ . '/auth.php';
