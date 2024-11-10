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
                    <h1 class="text-lg font-bold mb-3">Edit Product - {{ $product->name }}</h1>
                    <form wire:submit="update">
                        <div class="mb-3">
                            <label for="name" class="block">Product Name</label>
                            <input type="text" name="name" id="name" wire:model="name"
                                class="sm:w-1/2 w-full border rounded-lg px-4 py-2 @error('name') border-red-500 @enderror"
                                value="{{ $product->name }}">
                            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="cost" class="block">Harga Modal</label>
                            <input type="number" name="cost" id="cost" wire:model="cost"
                                class="sm:w-1/2 w-full border rounded-lg px-4 py-2 @error('cost') border-red-500 @enderror"
                                value="{{ $product->cost }}">
                            @error('cost') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="price" class="block">Harga Jual</label>
                            <input type="number" name="price" id="price" wire:model="price"
                                class="sm:w-1/2 w-full border rounded-lg px-4 py-2 @error('price') border-red-500 @enderror"
                                value="{{ $product->price }}">
                            @error('price') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit"
                            class="w-1/4 bg-green-500 text-white p-2 rounded-lg hover:bg-green-400">Update</button>
                        <a wire:navigate href="{{ route('product') }}"
                            class="w-1/4 bg-red-500 text-white py-2 px-10 rounded-lg hover:bg-red-400">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>