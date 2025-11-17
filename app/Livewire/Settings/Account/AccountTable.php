<?php

namespace App\Livewire\Settings\Account;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Log;

class AccountTable extends Component
{
    use WithPagination;
    public $search = '';
    public $selectedId;

    public function destroy($id)
    {
        $account = ChartOfAccount::find($id);

        $journalExists = $account->debt()->exists() || $account->cred()->exists() || $account->is_locked;

        if ($journalExists) {
            return;
        }

        try {
            $account->balance()->delete();
            $account->delete();

            $this->dispatch('account-deleted', $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function select($id)
    {
        $this->selectedId = $id;
        $this->dispatch('account-selected', $id);
        $this->dispatch('open-modal', 'edit-account');
    }

    #[On(['account-created', 'account-deleted', 'account-updated'])]
    public function render()
    {
        return view('livewire.settings.account.account-table', [
            'accounts' => ChartOfAccount::when($this->search, fn($query) => $query->where('acc_name', 'like', '%' . $this->search . '%'))->orderBy('acc_code', 'asc')->paginate(10)->onEachSide(0)
        ]);
    }
}
