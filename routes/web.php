<?php

use App\Livewire\Report\Report;
use App\Livewire\Report\Cashflow;
use App\Livewire\Setting\Setting;
use App\Livewire\Report\ProfitLoss;
use App\Livewire\Transaction\Sales;
use App\Livewire\Report\BalanceSheet;
use Illuminate\Support\Facades\Route;
use App\Livewire\Report\GeneralLedger;
use App\Livewire\Setting\User\EditUser;
use App\Livewire\Transaction\Purchases;
use App\Livewire\Setting\User\UserTable;
use App\Livewire\Transaction\Transaction;
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

    //Transaction
    Route::get('/transaction', Transaction::class)->name('transaction');
    Route::get('/transaction/purchases', App\Livewire\Transaction\Purchases::class)->name('transaction.purchases');
    Route::get('/transaction/sales', App\Livewire\Transaction\Sales::class)->name('transaction.sales');

    // Report
    Route::get('/report', App\Livewire\Report\Report::class)->name('report');
    Route::get('/report/cashflow', Cashflow::class)->name('cashflow');
    Route::get('/report/balance-sheet', BalanceSheet::class)->name('balance-sheet');
    Route::get('/report/profit-loss', ProfitLoss::class)->name('profit-loss');
    Route::get('/report/general-ledger', GeneralLedger::class)->name('general-ledger');

    //Journal
    Route::get('/journal', App\Livewire\Journal\Journal::class)->name('journal');
    Route::get('/journal/{journal_id}/edit', App\Livewire\Journal\EditJournal::class)->name('journal.edit');
});

require __DIR__ . '/auth.php';
