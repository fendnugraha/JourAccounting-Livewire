<form wire:submit="createMutation">
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3">
        <x-input-label for="date_issued" :value="__('Tanggal')" />
        <x-text-input wire:model="date_issued" type="datetime-local" class="mt-1 block w-full" />
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('date_issued')" />
    </div>

    <div class="mb-4 grid grid-cols-1 sm:grid-cols-3 items-center">
        <x-input-label for="warehouse" :value="__('Cabang')" />
        <div class="sm:col-span-2">
            <select wire:model.live="warehouse"
                class="mt-1 block py-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Pilih cabang</option>
                @foreach ($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- rekening pusat -->
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center">
        <x-input-label for="cred_code" :value="__('Dari Rekening (Pusat)')" />
        <div class="sm:col-span-2">
            <select wire:model="cred_code"
                class="mt-1 block py-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Pilih akun</option>
                @foreach ($hq_accounts as $hq_account)
                <option value="{{ $hq_account->id }}">{{ $hq_account->acc_name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('cred_code')" />
        </div>
    </div>

    <!-- rekening cabang -->
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center">
        <x-input-label for="debt_code" :value="__('Ke Rekening (Cabang)')" />
        <div class="sm:col-span-2">
            <select wire:model="debt_code"
                class="mt-1 block py-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                wire:key="{{ $warehouse }}">
                <option value="">Pilih akun</option>
                @foreach ($branch_accounts as $account)
                <option value="{{ $account->id }}">{{ $account->acc_name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('debt_code')" />
        </div>
    </div>

    <!-- jumlah -->
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center" x-data="{ amount: @entangle('amount') }">
        <x-input-label for="amount" :value="__('Jumlah')" />
        <div>
            <x-text-input wire:model="amount" type="number" placeholder="Rp." class="mt-1 block w-full" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('amount')" />
        </div>
        <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(amount ?? 0)"
            class="text-sky-500 italic font-bold text-sm sm:text-lg sm:text-right"></span>
    </div>

    <!-- admin bank -->
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center" x-data="{ bank_fee: @entangle('bank_fee') }">
        <x-input-label for="bank_fee" :value="__('Admin Bank')" />
        <div>
            <x-text-input wire:model="bank_fee" type="number" placeholder="Rp." class="mt-1 block w-1/2" />
        </div>
        <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(bank_fee ?? 0)"
            class="text-sky-500 italic font-bold text-sm sm:text-lg sm:text-right"></span>
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-action-message class="me-3" on="journal-created">
            {{ __('Saved.') }}
        </x-action-message>

        <x-primary-button type="submit" wire:loading.attr="disabled">
            <span>Create</span>
        </x-primary-button>
    </div>
</form>