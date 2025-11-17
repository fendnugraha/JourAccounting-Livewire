<div>
    <form wire:submit="editUser">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select wire:model="role" id="role"
                class="block mt-1 w-full border-gray-300 py-1 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">-- Pilih Level --</option>
                <option value="Kasir">Kasir</option>
                <option value="Administrator">Administrator</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="warehouse_id" :value="__('Warehouse')" />
            <select wire:model="warehouse_id" id="warehouse_id"
                class="block mt-1 w-full border-gray-300 py-1 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">-- Pilih Cabang --</option>
                @foreach ($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-action-message class="me-3" on="user-updated">
                {{ __('User updated.') }}
            </x-action-message>
            <x-primary-button class="ms-4">
                {{ __('Update') }}
            </x-primary-button>
        </div>
    </form>
</div>