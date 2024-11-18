<?php

namespace App\Livewire\Journal;

use App\Models\Journal;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;

class CreateMutation extends Component
{
    public $date_issued;
    public $debt_code;
    public $cred_code;
    public $amount;
    public $description;

    public function mount()
    {
        $this->date_issued = date('Y-m-d H:i');
    }

    #[On('JournalCreated')]
    public function resetDateIssued()
    {
        $this->date_issued = date('Y-m-d H:i');
    }

    public function save()
    {
        $user = Auth::user();

        $this->validate([
            'date_issued' => 'required',
            'debt_code' => 'required|different:cred_code',
            'cred_code' => 'required|different:debt_code',
            'amount' => 'required',
        ], [
            'debt_code.different' => 'Kode akun debet dan kredit tidak boleh sama.',
            'cred_code.different' => 'Kode akun debet dan kredit tidak boleh sama.',
        ]);

        $journal = new Journal([
            'invoice' => Journal::invoice_journal(),
            'date_issued' => $this->date_issued,
            'debt_code' => $this->debt_code,
            'cred_code' => $this->cred_code,
            'amount' => $this->amount,
            'fee_amount' => 0,
            'trx_type' => 'Mutasi Kas',
            'description' => $this->description ?? 'Mutasi Kas & Bank',
            'user_id' => $user->id,
            'warehouse_id' => $user->role->warehouse_id
        ]);

        $journal->save();

        session()->flash('success', 'Journal Created Successfully');

        $this->dispatch('JournalCreated', $journal);

        $this->reset();
    }

    public function render()
    {
        return view('livewire.journal.create-mutation', [
            'credits' => ChartOfAccount::whereIn('account_id', [1, 2])->where('warehouse_id', Auth::user()->role->warehouse_id)->get(),
        ]);
    }
}
