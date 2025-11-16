<form wire:submit.prevent="createContact">
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
        <x-input-label for="type" :value="__('Tipe')" />
        <select wire:model="type"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Pilih tipe</option>
            <option value="Supplier">Supplier</option>
            <option value="Customer">Customer</option>
            <option value="Employee">Karyawan</option>
        </select>
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('type')" />
    </div>
    <div class="mb-2">
        <x-input-label for="description" :value="__('Deskripsi')" />
        <x-text-input wire:model="description" type="text" class="mt-1 block w-full" placeholder="(Optional)" />
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('description')" />
    </div>
    <div class="flex items-center justify-end mt-4">
        <x-action-message class="me-3" on="contact-created">
            {{ __('Saved.') }}
        </x-action-message>
        <x-primary-button type="submit">
            {{ __('Create') }}
        </x-primary-button>
    </div>
</form>