<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-lg font-bold mb-3">Edit User - {{ $user->name }}</h1>
                    <form wire:submit="update">
                        <div class="mb-3">
                            <label for="role" class="block">Role</label>
                            <select name="role" id="role" wire:model="role"
                                class="sm:w-1/2 w-full border rounded-lg px-4 py-2 @error('role') border-red-500 @enderror">
                                <option value="Kasir" @if ($user->role == 'Kasir') selected @endif>Kasir</option>
                                <option value="Administrator" @if ($user->role == 'Administrator') selected
                                    @endif>Administrator
                                </option>
                            </select>
                            @error('role') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="warehouse_id" class="block">Warehouse</label>
                            <select name="warehouse_id" id="warehouse_id" wire:model="warehouse_id"
                                class="sm:w-1/2 w-full border rounded-lg px-4 py-2 @error('warehouse_id') border-red-500 @enderror">
                                @foreach ($warehouses as $w)
                                <option value="{{ $w->id }}" @if ($user->warehouse_id == $w->id) selected @endif>{{
                                    $w->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('warehouse_id') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-500 rounded-lg px-4 py-2 text-white">Save</button>
                            <a wire:navigate href="{{ route('user.index') }}"
                                class="bg-red-500 rounded-lg px-4 py-2 text-white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>