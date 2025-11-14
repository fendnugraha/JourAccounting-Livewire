<form wire:submit="createDeposit">
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3">
        <x-input-label for="date_issued" :value="__('Tanggal')" />
        <x-text-input wire:model="date_issued" type="datetime-local" class="mt-1 block w-full" />
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('date_issued')" />
    </div>
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center" x-data="{ price: @entangle('price') }">
        <x-input-label for="price" :value="__('Harga Jual')" />
        <div>
            <x-text-input wire:model="price" type="number" placeholder="Rp." class="mt-1 block w-full" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('price')" />
        </div>
        <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(price ?? 0)"
            class="text-sky-500 italic font-bold text-sm sm:text-lg sm:text-right"></span>
    </div>
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center" x-data="{ cost: @entangle('cost') }">
        <x-input-label for="cost" :value="__('Harga Modal')" />
        <div>
            <x-text-input wire:model="cost" type="number" placeholder="Rp." class="mt-1 block w-full" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('cost')" />
        </div>
        <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(cost ?? 0)"
            class="text-sky-500 italic font-bold text-sm sm:text-lg sm:text-right"></span>
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
            {{ session('success') ?? 'Berhasil.' }}
        </x-action-message>
        <x-primary-button type="submit" wire:loading.attr="disabled">
            {{ __('Create') }}
        </x-primary-button>
    </div>
</form>