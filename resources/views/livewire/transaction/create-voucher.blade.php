<form wire:submit="createVoucher">
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3">
        <x-input-label for="date_issued" :value="__('Tanggal')" />
        <x-text-input wire:model="date_issued" type="datetime-local" class="mt-1 block w-full" />
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('date_issued')" />
    </div>
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center">
        <x-input-label for="product_id" :value="__('Produk Voucher')" />
        <div class="sm:col-span-2">
            <select wire:model.live="product_id"
                class="mt-1 block py-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Pilih produk</option>
                @foreach ($products as $product) <option value="{{ $product->id }}">{{ $product->name }} -> Rp {{
                    Number::format($product->price) }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('product_id')" />
        </div>
    </div>
    <div class="mb-2 grid grid-cols-1 sm:grid-cols-3 items-center" x-data="{ quantity: @entangle('quantity') }">
        <x-input-label for="quantity" :value="__('Quantity')" />
        <div>
            <x-text-input wire:model="quantity" type="number" placeholder="Rp." class="mt-1 block w-full" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('quantity')" />
        </div>
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