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
                    <h1 class="text-lg font-bold mb-3">Edit Warehouse - {{ $warehouse->name }}</h1>
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-3">
                        <div class="bg-slate-200 shadow-300 rounded-lg p-3 ">
                            <form wire:submit="update">
                                <div class="mb-3">
                                    <label for="name" class="block">Warehouse Name</label>
                                    <input type="text" name="name" id="name"
                                        class="w-full border rounded-lg p-2 @error('name') border-red-500 @enderror"
                                        value="{{ $warehouse->name }}">
                                    @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="cashAccount" class="block">Cash Account</label>
                                    <select name="cashAccount" class="w-full border rounded-lg p-2">
                                        @foreach ($ChartOfAccount as $c)
                                        <option value=" {{ $c->id }}" {{ $c->id == $warehouse->chart_of_account_id ?
                                            'selected' : ''
                                            }}>{{
                                            $c->acc_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('cashAccount') <span class="text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="block">Address</label>
                                    <textarea name="address" id="address"
                                        class="w-full border rounded-lg p-2 @error('address') border-red-500 @enderror">{{ $warehouse->address }}</textarea>
                                    @error('address') <span class="text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit"
                                    class="w-1/4 bg-green-500 text-white p-2 rounded-lg hover:bg-green-400"><i
                                        class="fa-solid fa-floppy-disk"></i> Update</button>
                                <a wire:navigate href="{{ route('warehouse') }}"
                                    class="w-1/4 bg-red-500 text-white py-2 px-10 rounded-lg hover:bg-red-400"><i
                                        class="fa-solid fa-backward"></i> Back</a>
                            </form>
                        </div>
                        <div>
                            <livewire:setting.warehouse.wh-bank-list :warehouse="$warehouse" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>