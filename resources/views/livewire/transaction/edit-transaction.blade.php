<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="table-auto w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="p-4">Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction as $t)
                            <tr class="border-b" wire:key="transaction-{{ $t->id }}">
                                <td class="p-2">
                                    <select class="w-full rounded-lg text-sm"
                                        wire:model="productsSelected.{{ $t->product_id }}"
                                        wire:change="updateProduct({{ $t->id }}, $event.target.value)">
                                        @foreach ($products as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="w-full rounded-lg text-sm"
                                        wire:model="quantities.{{ $t->product_id }}" min="1"
                                        wire:change="updateQuantity({{ $t->id }}, $event.target.value)">
                                </td>
                                <td class="text-end p-2">
                                    <input type="number" class="w-full rounded-lg text-sm"
                                        wire:model="prices.{{ $t->product_id }}" min="0.01" step="0.01"
                                        wire:change="updatePrice({{ $t->id }}, $event.target.value)">
                                </td>
                                <td class="text-end p-2">
                                    {{ number_format(($quantities[$t->product_id] ?? 0) * ($prices[$t->product_id] ??
                                    0)) }}
                                </td>
                                <td class="p-2 text-center">
                                    <button type="button" class="text-red-600" wire:click="removeItem({{ $t->id }})">
                                        <i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-b">
                                <td colspan="3" class="text-end font-bold p-3">Total:</td>
                                <td class="text-end font-bold p-3">{{ number_format($totalAmount) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    @if(session('success'))
                    <span>{{ session('success') }}</span>
                    @elseif (session('error'))
                    <span>{{ session('error') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>