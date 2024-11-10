<?php

namespace App\Livewire\Setting\Account;

use Livewire\Component;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Layout;

class EditAccount extends Component
{
    public $account_id;
    public $name;
    public $st_balance;

    public function mount($account_id)
    {
        $this->account_id = $account_id;
        $account = ChartOfAccount::find($account_id);
        $this->name = $account->acc_name;
        $this->st_balance = $account->st_balance;
    }

    public function update()
    {
        $ChartOfAccount = ChartOfAccount::find($this->account_id);

        $this->validate([
            'name' => 'required|max:60|min:5|unique:chart_of_accounts,acc_name,' . $ChartOfAccount->id,
            'st_balance' => 'numeric',
        ]);

        $ChartOfAccount->update([
            'acc_name' => $this->name,
            'st_balance' => $this->st_balance,
        ]);

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Account has been updated!']);

        return redirect()->route('account');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $account = ChartOfAccount::find($this->account_id);

        return view('livewire.setting.account.edit-account', [
            'title' => 'Edit Account',
            'account' => $account,
        ]);
    }
}
