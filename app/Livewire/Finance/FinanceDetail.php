<?php

namespace App\Livewire\Finance;

use Carbon\Carbon;
use App\Models\Contact;
use App\Models\Finance;
use App\Models\Journal;
use Livewire\Component;
use App\Models\LogActivity;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinanceDetail extends Component
{
    public $contact;
    public $finance_type;
    public $contactName;
    public $startDate;
    public $endDate;


    public function mount()
    {
        $this->contactName = Contact::find($this->contact)->name ?? 'All';
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    #[On('contact-changed')]
    public function updatedContact($contact)
    {
        $this->contact = $contact;
        $this->contactName = Contact::find($this->contact)->name ?? 'All';
    }

    #[Computed]
    public function finances()
    {
        $finance = Finance::with(['contact', 'account'])
            ->where(fn($query) => $this->contact == "All" ?
                $query : $query->where('contact_id', $this->contact))
            ->where('finance_type', $this->finance_type)
            ->whereBetween('date_issued', [$this->startDate, $this->endDate])
            ->latest('date_issued')
            ->paginate(10)
            ->onEachSide(0);

        return $finance;
    }

    public function delete($id)
    {
        $finance = Finance::find($id);
        $invoice = $finance->invoice;

        $checkData = Finance::where('invoice', $invoice)->get();
        // dd($checkData->count());

        if ($finance->payment_status == 1) {
            session()->flash('error', 'Invoice ini telah dibayar, tidak dapat dihapus');
            return;
        }


        if ($finance->payment_status == 0 && $finance->payment_nth == 0 && $checkData->count() > 1) {
            session()->flash('error', 'Invoice ini memiliki pembayaran sebelumnya, tidak dapat dihapus');
            return;
        }

        $log = new LogActivity();

        DB::beginTransaction();
        try {
            Journal::where('invoice', $invoice)->where('payment_status', $finance->payment_status)->where('payment_nth', $finance->payment_nth)->delete();
            $finance->delete();

            $financeAmount = $finance->bill_amount > 0 ? $finance->bill_amount : $finance->payment_amount;
            $billOrPayment = $finance->bill_amount > 0 ? 'bill' : 'payment';
            $log->create([
                'user_id' => auth()->user()->id,
                'warehouse_id' => 1,
                'activity' => $finance->finance_type . ' deleted',
                'description' => $finance->finance_type . ' with invoice: ' . $finance->invoice . ' ' . $billOrPayment . ' amount: ' . $financeAmount . ' deleted by ' . auth()->user()->name,
            ]);

            DB::commit();

            $this->dispatch('finance-deleted', [
                'invoice' => $invoice
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
        }
    }

    #[On(['finance-created', 'finance-deleted'])]
    public function render()
    {
        return view('livewire.finance.finance-detail');
    }
}
