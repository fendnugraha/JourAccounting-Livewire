<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <x-notification>
                <x-slot name="classes">bg-green-500 text-white absolute bottom-3 left-4</x-slot>
                <strong>Success!!</strong> {{ session('success') }}
            </x-notification>
            @elseif (session('error'))
            <x-notification>
                <x-slot name="classes">bg-red-500 text-white absolute bottom-3 left-4</x-slot>
                <strong>Error!!</strong> {{
                session('error') }}
            </x-notification>
            @endif
            <div class="grid grid-cols-3 mb-3 sm:w-3/4">
                <div class="flex gap-2 col-span-2">
                    <x-modal modalName="addProduct" modalTitle="Form Tambah Produk">
                        <livewire:setting.product.create-product />
                    </x-modal>
                    <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'addProduct'})" bg-sky-950 text-w
                        class="bg-blue-950 hover:bg-blue-800 text-white rounded-lg py-1 px-3 h-full">
                        <i class="fa-solid fa-plus-circle"></i> Tambah produk
                    </button>
                    <x-modal modalName="addCategory" modalTitle="Form Tambah Kategori">
                        <form wire:submit="addCategory">
                            <div class="mb-2">
                                <label for="slug"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Code
                                </label>
                                <input type="text" placeholder="Kode" wire:model="slug"
                                    class="w-full border rounded-lg p-2" name="slug" minlength="3" maxlength="3">

                            </div>
                            <div class="mb-6">
                                <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Nama Kategori
                                </label>
                                <input type="text" placeholder="Nama Kategori" wire:model="name"
                                    class="w-full border rounded-lg p-2" name="name">

                            </div>
                            <div>
                                <button type="submit"
                                    class="w-full bg-blue-500 text-white p-2 rounded-lg disabled:bg-slate-400 disabled:cursor-none"
                                    wire:loading.attr="disabled">Simpan <span wire:loading><i
                                            class="fa-solid fa-spinner animate-spin"></i></span></button>
                            </div>
                        </form>
                    </x-modal>
                    <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'addCategory'})" bg-sky-950 text-w
                        class="bg-blue-950 hover:bg-blue-800 text-white rounded-lg py-1 px-3 h-full">
                        <i class="fa-solid fa-plus-circle"></i> Tambah Kategori
                    </button>

                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between mb-2 gap-2">
                        <div class="w-full">
                            <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search .."
                                class=" w-full text-sm border rounded-lg p-2">
                        </div>
                        <div class="">
                            <select wire:model.live="perPage" class="text-sm border w-20 rounded-lg p-2">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    <table class="table-auto w-full text-sm mb-2">
                        <thead class="bg-white text-blue-950">
                            <tr class="border-b">
                                <th class="p-4">Name <i class="fa-solid fa-sort"></i></th>
                                <th>Harga Modal </th>
                                <th>Harga Jual</th>
                                <th>Last Update</th>
                                <th>Action</th>

                            </tr>
                        </thead>

                        <tbody class="bg-white">
                            @foreach ($products as $product)
                            @if ($product->status == 1)
                            @php $status = '<sup class="text-green-600 font-bold">Active</sup>' @endphp
                            @else
                            @php $status = '<sup class="text-red-600 font-bold">Inactive</sup>' @endphp
                            @endif

                            @php
                            $priceWarning = $product->price > $product->cost ? '' : 'text-red-600';
                            $warning = $product->price > $product->cost ? '' : '<i
                                class="fa-solid fa-circle-exclamation mr-1"></i>';
                            @endphp
                            <tr class="border-b {{ $priceWarning }} border-slate-200 hover:bg-yellow-100">
                                <td class="p-3">{{ $product->code }} <span class="font-bold text-md text-blue-950">{{
                                        $product->name
                                        }}</span>
                                    <br>
                                    <span class="text-slate-600">{{ ucwords($product->category) }} &nbsp;&nbsp;{{
                                        $product->end_stock . ' Pcs' }} &nbsp;&nbsp;{{
                                        number_format($product->sold) }} terjual</span>
                                </td>
                                <td class="text-right p-3 text-xs">{{ number_format($product->cost, 2) }}</td>
                                <td class="text-right p-3 text-xs">{!! $warning !!} {{ number_format($product->price, 2)
                                    }}</td>
                                <td class="text-center p-3 text-xs">{{ $product->updated_at->diffForHumans() ?? '' }}
                                </td>
                                <td class="text-center">
                                    <a wire:navigate href="{{ route('product.edit', $product->id) }}"
                                        class="text-slate-800 font-bold bg-yellow-400 py-1 px-3 rounded-lg hover:bg-yellow-300"><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                    <button wire:click="delete({{ $product->id }})" wire:loading.attr="disabled"
                                        wire:confirm="Apakah anda yakin menghapus data ini?"
                                        class="text-white font-bold bg-red-400 py-1 px-3 rounded-lg hover:bg-red-300"><i
                                            class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $products->onEachSide(0)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>