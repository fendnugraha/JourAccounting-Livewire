<form wire:submit.prevent="createAccount">
    <div class="mb-2">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input wire:model="name" type="text" class="mt-1 block w-full" autofocus />
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('name')" />
    </div>
    <div class="mb-2">
        <x-input-label for="category" :value="__('Category')" />
        <select wire:model="account_id"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Select Category</option>
            @foreach ($accounts as $account) <option value="{{ $account->id }}">{{ $account->name }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('account_id')" />
    </div>
    <div class="mb-2">
        <x-input-label for="st_balance" :value="__('Saldo')" />
        <x-text-input wire:model="st_balance" type="number" class="mt-1 block w-full" />
        <x-input-error class="mt-1 text-xs" :messages="$errors->get('st_balance')" />
    </div>
    <div class="flex items-center justify-end mt-4">
        <x-action-message class="me-3" on="account-created">
            {{ __('Saved.') }}
        </x-action-message>
        <x-primary-button type="submit">
            {{ __('Create') }}
        </x-primary-button>
    </div>
</form>