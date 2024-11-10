<?php

use App\Livewire\Setting\Setting;
use Illuminate\Support\Facades\Route;
use App\Livewire\Setting\User\EditUser;
use App\Livewire\Setting\User\UserTable;
use App\Livewire\Setting\Account\EditAccount;
use App\Livewire\Setting\Contact\EditContact;
use App\Livewire\Setting\Product\EditProduct;
use App\Livewire\Setting\Account\AccountTable;
use App\Livewire\Setting\Contact\ContactTable;
use App\Livewire\Setting\Product\ProductTable;
use App\Livewire\Setting\Warehouse\EditWarehouse;
use App\Livewire\Setting\Warehouse\WarehouseTable;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::group(['middleware' => ['auth']], function () {
    Route::get('setting', App\Livewire\Setting\Setting::class)->name('setting');

    // Account
    Route::get('/setting/account', AccountTable::class)->name('account');
    Route::get('/setting/account/{account_id}/edit', EditAccount::class)->name('account.edit');

    // Warehouse
    Route::get('/setting/warehouse', WarehouseTable::class)->name('warehouse');
    Route::get('/setting/warehouse/{warehouse_id}/edit', EditWarehouse::class)->name('warehouse.edit');

    // User
    Route::get('/setting/user', UserTable::class)->name('user.index');
    Route::get('/setting/user/{user_id}/edit', EditUser::class)->name('user.edit');

    // Contact
    Route::get('/setting/contact', ContactTable::class)->name('contact');
    Route::get('/setting/contact/{contact_id}/edit', EditContact::class)->name('contact.edit');

    //Product
    Route::get('/product', ProductTable::class)->name('product');
    Route::get('/product/{product_id}/edit', EditProduct::class)->name('product.edit');
});

require __DIR__ . '/auth.php';
