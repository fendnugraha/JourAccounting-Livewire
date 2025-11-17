<div class="bg-white rounded-lg p-4">
    <div class="flex gap-2 mb-4">
        <a href="{{ route('settings.warehouse.index') }}" class="self-center hover:underline" wire:navigate><i
                class="bi bi-arrow-left w-5 h-5"></i> Kembali</a>
    </div>
    <form wire:submit.prevent="editWarehouse">
        <div class="mb-2">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" type="text" class="mt-1 block w-full" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('name')" />
        </div>
        <div class="mb-2">
            <x-input-label for="address" :value="__('Alamat')" />
            <x-text-input wire:model="address" type="text" class="mt-1 block w-full" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('address')" />
        </div>
        <div class="mb-2">
            <x-input-label for="account_id" :value="__('Akun Kas')" />
            <select wire:model="account_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Pilih akun</option>
                @foreach ($accounts as $account) <option value="{{ $account->id }}">{{ $account->acc_name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('account_id')" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-action-message class="me-3" on="warehouse-updated">
                {{ __('Warehouse updated.') }}
            </x-action-message>
            <x-primary-button type="submit">
                {{ __('Update') }}
            </x-primary-button>
        </div>
    </form>
</div>