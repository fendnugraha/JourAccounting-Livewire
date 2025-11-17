<form wire:submit.prevent="editProduct">
    <div class="mb-2">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input wire:model="name" type="text" class="mt-1 block w-full" />
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('name')" />
    </div>
    <div class="grid grid-cols-2 gap-4 mb-2">
        <div class="">
            <x-input-label for="cost" :value="__('Harga Modal')" />
            <x-text-input wire:model="cost" type="number" class="mt-1 block w-full" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('cost')" />
        </div>
        <div class="">
            <x-input-label for="price" :value="__('Harga Jual')" />
            <x-text-input wire:model="price" type="number" class="mt-1 block w-full" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('price')" />
        </div>
    </div>
    <div class="flex items-center justify-end mt-4">
        <x-action-message class="me-3" on="product-updated">
            {{ __('Product updated successfully.') }}
        </x-action-message>
        <x-primary-button type="submit">
            {{ __('Update') }}
        </x-primary-button>
    </div>
</form>