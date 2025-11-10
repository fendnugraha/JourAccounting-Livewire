<?php

namespace App\Livewire\Settings\Account;

use App\Models\Account;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateAccount extends Component
{
    #[Validate('required|unique:chart_of_accounts,acc_name|min:5|max:255')]
    public string $name = '';

    #[Validate('required|exists:accounts,id')]
    public $account_id;

    #[Validate('required|numeric|min:0')]
    public $st_balance = 0;

    public function createAccount()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $account = ChartOfAccount::create([
                'acc_code' => ChartOfAccount::acc_code($this->account_id),
                'acc_name' => $this->name,
                'account_id' => $this->account_id,
                'st_balance' => $this->st_balance
            ]);

            DB::commit();

            $this->reset();
            $this->resetValidation();

            $this->dispatch('account-created', id: $account->id);

            session()->flash('success', 'Account created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            session()->flash('error', 'Failed to create account.');

            $this->resetValidation();
            return;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    public function render()
    {
        return view('livewire.settings.account.create-account', [
            'title' => 'Create Chart of Account',
            'accounts' => Account::all()
        ]);
    }
}
