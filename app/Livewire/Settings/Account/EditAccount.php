<?php

namespace App\Livewire\Settings\Account;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Validate;

class EditAccount extends Component
{
    public $id;

    #[Validate('required|unique:chart_of_accounts,acc_name|min:5|max:255')]
    public string $name = '';

    #[Validate('required|exists:accounts,id')]
    public $account_id;

    #[Validate('required|numeric|min:0')]
    public $st_balance = 0;

    #[On('account-selected')]
    public function loadAccount($id)
    {
        $this->id = $id;

        $account = ChartOfAccount::find($id);

        $this->name = $account->acc_name;
        $this->account_id = $account->account_id;
        $this->st_balance = $account->st_balance;
    }

    public function editAccount()
    {
        $account = ChartOfAccount::find($this->id);

        $account->update([
            'acc_name' => $this->name,
            'account_id' => $this->account_id,
            'st_balance' => $this->st_balance
        ]);

        $this->dispatch('account-updated', $account->id);

        session()->flash('success', 'Account updated successfully.');
    }

    public function render()
    {
        return view('livewire.settings.account.edit-account');
    }
}
