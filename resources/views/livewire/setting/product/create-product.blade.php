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
        <div class="mb-4">
            <label for="name" class="block">Name</label>
            <input type="text" wire:model="name"
                class="w-full border rounded-lg p-2 @error('name') border-red-500 @enderror">
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="cost" class="block">Harga Modal</label>
            <input type="number" wire:model="cost"
                class="w-full border rounded-lg p-2 @error('cost') border-red-500 @enderror">
            @error('cost') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="price" class="block">Harga Jual</label>
            <input type="number" wire:model="price"
                class="w-full border rounded-lg p-2 @error('price') border-red-500 @enderror">
            @error('price') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="category" class="block">Category</label>
            <select wire:model="category"
                class="w-full border rounded-lg p-2 @error('category') border-red-500 @enderror">
                <option value="">--Pilih Category--</option>
                @foreach ($categories as $category)
                <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="grid grid-cols-2 gap-2 mt-4">
            <button type="submit"
                class="w-full bg-slate-700 hover:bg-slate-600 text-white p-2 rounded-lg">Simpan</button>
        </div>
    </form>
</div>