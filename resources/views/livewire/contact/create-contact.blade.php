<div>
    @if(session('success'))
    <x-notification class="bg-green-500 text-white mb-3">
        <strong><i class="fas fa-check-circle"></i> Success!!</strong>
    </x-notification>
    @elseif (session('error'))
    <x-notification class="bg-red-500 text-white mb-3">
        <strong>Error!!</strong> {{
        session('error') }}
    </x-notification>
    @endif
    <div class="bg-violet-500 text-white p-2 rounded-lg mb-3 w-full" wire:loading>
        Menyimpan data ..
    </div>

    <form wire:submit="save">
        <div class="mb-2">
            <label for="name" class="block">Name</label>
            <input type="text" wire:model="name"
                class="w-full border rounded-lg p-2 @error('name') border-red-500 @enderror">
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="mb-2">
            <label for="type" class="block">Type</label>
            <select wire:model="type" class="w-full border rounded-lg p-2 @error('type') border-red-500 @enderror">
                <option value="">--Pilih Type--</option>
                <option value="Customer">Customer</option>
                <option value="Supplier">Supplier</option>
            </select>
            @error('type') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="mb-2">
            <label for="description" class="block">Description</label>
            <textarea wire:model="description"
                class="w-full border rounded-lg p-2 @error('description') border-red-500 @enderror"></textarea>
            @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-2 mt-4">
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg">Simpan</button>
        </div>

    </form>
</div>