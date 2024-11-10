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
                    <h1 class="text-lg font-bold mb-3">Edit Account - {{ $account->acc_name }}</h1>
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-3">
                        <div class="">
                            <form wire:submit.prevent="update">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="block">Account Name</label>
                                    <input type="text" wire:model="name" name="name" id="name"
                                        class="border rounded-lg px-4 py-2 @error('name') border-red-500 @enderror w-full"
                                        value="{{ $account->acc_name }}">
                                    @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="st_balance" class="block">Saldo Awal</label>
                                    <input type="number" wire:model="st_balance" name="st_balance" id="st_balance"
                                        class="border rounded-lg px-4 py-2 @error('st_balance') border-red-500 @enderror w-full"
                                        value="{{ $account->st_balance }}">
                                    @error('st_balance') <span class="text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex gap-3 ">
                                    <button type="submit"
                                        class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-400 w-full">Update</button>
                                    <a wire:navigate href="{{ route('account') }}"
                                        class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-400 w-full text-center">Batal</a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>