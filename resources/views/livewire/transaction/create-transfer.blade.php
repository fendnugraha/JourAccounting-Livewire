<form wire:submit="createTransfer">
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3">
        <x-input-label for="date_issued" :value="__('Tanggal')" />
        <x-text-input wire:model="date_issued" type="datetime-local" class="mt-1 block w-full" />
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('date_issued')" />
    </div>
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center">
        <x-input-label for="cred_code" :value="__('Dari Rekening')" />
        <div class="sm:col-span-2">
            <select wire:model="cred_code"
                class="mt-1 block py-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Pilih akun</option>
                @foreach ($accounts as $account) <option value="{{ $account->id }}">{{ $account->acc_name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('cred_code')" />
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

    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center" x-data="{ fee_amount: @entangle('fee_amount') }">
        <x-input-label for="fee_amount" :value="__('Fee (Admin)')" />
        <div class="">
            <x-text-input wire:model="fee_amount" type="number" placeholder="Rp." class="mt-1 block w-1/2" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('fee_amount')" />
        </div>
        <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(fee_amount ?? 0)"
            class="text-sky-500 italic font-bold text-sm sm:text-lg sm:text-right"></span>
    </div>
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center">
        <x-input-label for="custName" :value="__('Nama Rekening Tujuan')" />
        <div class="sm:col-span-2">
            <x-text-input wire:model="custName" type="text" placeholder="Isi nama rekening tujuan"
                class="mt-1 block w-full" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('custName')" />
        </div>
    </div>
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center">
        <x-input-label for="description" :value="__('Keterangan')" />
        <div class="sm:col-span-2">
            <x-text-input wire:model="description" type="text" placeholder="(Optional)" class="mt-1 block w-full" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('description')" />
        </div>
    </div>
    <div class="flex items-center justify-end mt-4">
        <x-action-message class="me-3" on="journal-created">
            {{ __('Saved.') }}
        </x-action-message>
        <x-primary-button type="submit" wire:loading.attr="disabled">
            {{ __('Create') }}
        </x-primary-button>
    </div>
</form>