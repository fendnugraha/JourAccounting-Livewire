<form wire:submit.prevent="editAccount">
    <div class="mb-2">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input wire:model="name" type="text" class="mt-1 block w-full" autofocus />
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('name')" />
    </div>
    <div class="mb-2">
        <x-input-label for="st_balance" :value="__('Saldo')" />
        <x-text-input wire:model="st_balance" type="number" class="mt-1 block w-full" />
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('st_balance')" />
    </div>
    <div class="flex items-center justify-end mt-4">
        <x-action-message class="me-3" on="account-updated">
            {{ __('Account updated.') }}
        </x-action-message>
        <x-primary-button type="submit">
            {{ __('Update') }}
        </x-primary-button>
    </div>
</form>