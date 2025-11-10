<?php

namespace App\Livewire\Settings\Account;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ChartOfAccount;

class AccountTable extends Component
{
    use WithPagination;
    public $search = '';

    public function destroy($id)
    {
        $account = ChartOfAccount::find($id);

        $journalExists = $account->debt()->exists() || $account->cred()->exists();

        if ($journalExists) {
            return;
        }

        $account->delete();
    }

    #[On('account-created')]
    public function render()
    {
        return view('livewire.settings.account.account-table', [
            'accounts' => ChartOfAccount::when($this->search, fn($query) => $query->where('acc_name', 'like', '%' . $this->search . '%'))->orderBy('acc_code', 'asc')->paginate(10)->onEachSide(0)
        ]);
    }
}
