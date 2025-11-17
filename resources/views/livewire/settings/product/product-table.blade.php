<div class="mt-4">
    <div class="my-2">
        <x-text-input wire:model.live.debounce.500ms="search" type="text" class="block w-full"
            placeholder="Search..." />
    </div>
    <div class="overflow-x-auto">
        <table class="table w-full text-xs mb-2">
            <thead class="bg-white text-blue-950">
                <tr class="">
                    <th class="p-4">Nama Barang</th>
                    <th>Type</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($products as $product)
                <tr>
                    <td class="p-3 font-bold text-sm">{{ $product->name }}
                        <span class="block font-normal text-xs">ID: {{ $product->id }}, Modal: {{
                            number_format($product->cost) }}, Jual: {{
                            number_format($product->price) }}</span>
                    </td>
                    <td>{{ $product->category }}</td>
                    <td class="text-center">
                        <button wire:click="setProductId({{ $product->id }})"
                            class="text-slate-800 font-bold text-xs bg-yellow-400 py-2 px-5 rounded-lg hover:bg-yellow-300"><i
                                class="bi bi-pencil-square"></i></button>
                        <button wire:click="destroy({{ $product->id }})" wire:loading.attr="disabled"
                            wire:confirm="Are you sure?"
                            class="text-white font-bold text-xs bg-red-400 py-2 px-5 rounded-lg hover:bg-red-300"><i
                                class="bi bi-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $products->links() }}
    </div>
    <x-modal name="edit-product" :show="false" :title="'Edit Product'">
        @livewire('settings.product.edit-product', ['product' => $selectedId])
    </x-modal>
</div>