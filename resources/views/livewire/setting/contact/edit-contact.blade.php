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
                    <h1 class="text-lg font-bold mb-3">Edit Contact - {{ $contact->name }}</h1>
                    <form wire:submit.prevent="update">
                        <div class="mb-3">
                            <label for="name" class="block">Contact Name</label>
                            <input type="text" name="name" id="name" wire:model="name"
                                class="sm:w-1/2 w-full border rounded-lg px-4 py-2 @error('name') border-red-500 @enderror"
                                value="{{ $contact->name }}">
                            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="type" class="block">Type</label>
                            <select name="type" id="type" wire:model="type"
                                class="sm:w-1/2 w-full border rounded-lg px-4 py-2 @error('type') border-red-500 @enderror">
                                <option value="">--Pilih Type--</option>
                                <option value="Customer" {{ $contact->type == 'Customer' ? 'selected' : '' }}>Customer
                                </option>
                                <option value="Supplier" {{ $contact->type == 'Supplier' ? 'selected' : '' }}>Supplier
                                </option>
                            </select>
                            @error('type') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="block">Description</label>
                            <textarea name="description" id="description" wire:model="description"
                                class="sm:w-1/2 w-full border rounded-lg px-4 py-2 @error('description') border-red-500 @enderror">{{ $contact->description }}</textarea>
                            @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-4 sm:w-1/2">
                            <button type="submit"
                                class="w-full bg-green-500 hover:bg-green-400 text-white p-2 rounded-lg"><i
                                    class="fa-solid fa-floppy-disk"></i> Update</button>
                            <a wire:navigate href="{{ route('contact') }}"
                                class="text-center w-full bg-red-500 hover:bg-red-400 text-white p-2 rounded-lg"><i
                                    class="fa-solid fa-ban"></i> Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>