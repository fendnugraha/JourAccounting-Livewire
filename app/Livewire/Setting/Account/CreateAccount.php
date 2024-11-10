<?php

namespace App\Livewire\Setting\Account;

use App\Models\Account;
use Livewire\Component;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Layout;

class CreateAccount extends Component
{
    public $name;
    public $account;
    public $st_balance;

    public function save()
    {
        $chartOfAccount = new ChartOfAccount();
        $this->validate([
            'name' => 'required|unique:chart_of_accounts,acc_name',
            'account' => 'required',
            'st_balance' => 'numeric',
        ]);

        $chartOfAccount->create([
            'acc_code' => $chartOfAccount->acc_code($this->account),
            'acc_name' => $this->name,
            'account_id' => $this->account,
            'st_balance' => $this->st_balance ?? 0,
        ]);

        session()->flash('success', 'Account Created Successfully!');

        $this->dispatch('AccountCreated', $chartOfAccount->id);

        $this->reset();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.setting.account.create-account', [
            'title' => 'Account',
            'accounts' => Account::all(),
        ]);
    }
}
