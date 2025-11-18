<form wire:submit="createPayment">
    <h1 class="text-xs font-bold mb-2">{{ $contactName }}</h1>
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3">
        <x-input-label for="date_issued" :value="__('Tanggal')" />
        <x-text-input wire:model="date_issued" type="datetime-local" class="mt-1 block w-full" />
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('date_issued')" />
    </div>
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center">
        <x-input-label for="invoice" :value="__('Invoice')" />
        <div class="sm:col-span-2">
            <select wire:model="invoice"
                class="mt-1 block py-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Pilih invoice</option>
                @foreach ($invoices as $invoice) <option value="{{ $invoice->invoice }}" {{ $invoice->sisa == 0 ?
                    'hidden' : '' }}>{{ $invoice->invoice }}, Sisa
                    Rp. {{ Number::format($invoice->sisa) }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('invoice')" />
        </div>
    </div>
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center">
        <x-input-label for="account_id" :value="__('Ke Rekening')" />
        <div class="sm:col-span-2">
            <select wire:model="account_id"
                class="mt-1 block py-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Pilih akun</option>
                @foreach ($accounts as $account) <option value="{{ $account->id }}">{{ $account->acc_name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('account_id')" />
        </div>
    </div>
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center" x-data="{ amount: @entangle('amount') }">
        <x-input-label for="amount" :value="__('Jumlah')" />
        <div>
            <x-text-input wire:model="amount" type="number" placeholder="Rp." class="mt-1 block w-full" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('amount')" />
        </div>
        <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(amount ?? 0)"
            class="text-sky-500 italic font-bold text-sm sm:text-lg sm:text-right"></span>
    </div>
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center">
        <x-input-label for="notes" :value="__('Keterangan')" />
        <div class="sm:col-span-2">
            <x-text-input wire:model="notes" type="text" placeholder="(Optional)" class="mt-1 block w-full" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('notes')" />
        </div>
    </div>
    <div class="flex items-center justify-end mt-4">
        <x-action-message class="me-3" on="finance-created">
            {{ session('success') ?? 'Pembayaran berhasil.' }}
        </x-action-message>
        <x-primary-button type="submit" wire:loading.attr="disabled">
            {{ __('Create') }}
        </x-primary-button>
    </div>
</form>