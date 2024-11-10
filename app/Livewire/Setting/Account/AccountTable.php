<?php

namespace App\Livewire\Setting\Account;

use App\Models\Journal;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Layout;

class AccountTable extends Component
{
    use WithPagination;

    public $search = '';

    public function delete($id)
    {
        $account = ChartOfAccount::findOrFail($id);
        if ($account) {
            $journalExists = Journal::where('debt_code', $account->acc_code)
                ->orWhere('cred_code', $account->acc_code)
                ->exists();
            if ($journalExists) {
                session()->flash('error', 'Account Cannot be Deleted!');
            } else {
                $account->delete();
                $this->dispatch('AccountCreated', ['id' => $account->id]);
                session()->flash('success', 'Account Deleted Successfully!');
            }
        }
    }

    #[Layout('layouts.app')]
    #[On('AccountCreated')]
    public function render()
    {
        $ChartOfAccount = ChartOfAccount::with('account')
            ->where('acc_name', 'like', '%' . $this->search . '%')
            ->orWhere('acc_code', 'like', '%' . $this->search . '%')
            ->orderBy('acc_code', 'asc')->paginate(10);

        return view('livewire.setting.account.account-table', [
            'title' => 'Account',
            'accounts' => $ChartOfAccount
        ]);
    }
}
