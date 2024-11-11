<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>
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
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-sub-navlinks :links="[
                ['href' => route('transaction'), 'route' => 'transaction', 'text' => 'Summary'],
                ['href' => route('transaction.sales'), 'route' => 'transaction.sales', 'text' => 'Sales'],
                ['href' => route('transaction.purchases'), 'route' => 'transaction.purchases', 'text' => 'Purchases']
            ]" />

            <div class="grid sm:grid-cols-4 grid-cols-1 gap-2">
                <div class="bg-white p-3 text-gray-900 shadow-sm sm:rounded-lg col-span-3">
                    <h1 class="text-lg font-bold mb-3">{{ __('Purchase Order') }}</h1>
                    <div class="relative z-50" x-data="{ showResults: false }">
                        <div class="mb-2">
                            <label class="block" for="contact_id">Supplier</label>
                            <select class="w-full sm:w-1/2 text-sm border rounded-lg p-2" wire:model="contact_id"
                                id="contact_id">
                                <option value="">--Pilih Supplier--</option>
                                @foreach ($contacts as $contact)
                                <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="search" class="w-full text-sm border rounded-lg p-2 mb-2"
                            wire:model.live.debounce.500ms="search"
                            x-on:input="showResults = $event.target.value.length > 0" placeholder="Search ..">


                        <!-- Display search results only when `showResults` is true -->
                        <div class="absolute top-18 left-0 w-full sm:w-3/4 bg-white border border-gray-200 rounded-lg shadow-lg"
                            x-show="showResults" x-transition x-on:click.away="showResults = false">
                            <table class="table-auto text-sm w-full">
                                <tbody>
                                    @foreach ($products as $product)
                                    <tr class="border-b hover:bg-yellow-100">
                                        <td class="p-2">{{ $product->name }}</td>
                                        <td>{{ number_format($product->cost, 2) }}</td>
                                        <td>
                                            <button
                                                class="text-white font-bold bg-green-400 py-1 px-3 rounded-lg hover:bg-green-300"
                                                wire:click="addToCart({{ $product->id }})">
                                                Add to cart
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (session('cart'))


                    <table class="table-auto w-full text-xs mb-2">
                        <thead class="bg-white text-blue-950">
                            <tr>
                                <th class="p-3">Product</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Cost</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @php
                            $total = 0;
                            @endphp
                            @foreach ($cart as $item)
                            @php
                            // Calculate subtotal for the current item
                            $subtotal = $item['qty'] * $item['cost'];
                            // Add to the total
                            $total += $subtotal;
                            @endphp
                            <tr class="border-b border-slate-100 odd:bg-white even:bg-blue-50">
                                <td class="p-3">{{ $item['name'] }}</td>
                                <td>
                                    <input type="number"
                                        class="w-full text-end text-xs rounded-lg bg-slate-300 border-0"
                                        wire:change="updateQty({{ $item['id'] }}, $event.target.value)"
                                        wire:model="cart.{{ $item['id'] }}.qty">
                                </td>
                                <td class="text-end p-3">
                                    <input type="number"
                                        class="w-full text-end text-xs rounded-lg bg-slate-300 border-0"
                                        wire:change="updateCost({{ $item['id'] }}, $event.target.value)"
                                        wire:model="cart.{{ $item['id'] }}.cost">
                                </td>
                                <td class="text-end p-3">
                                    {{ number_format($subtotal, 2) }}
                                </td>
                                <td class="text-center p-3">
                                    <button
                                        class="text-white font-bold bg-red-400 py-1 px-3 rounded-lg hover:bg-red-300"
                                        wire:click="removeFromCart({{ $item['id'] }})">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-100">
                            <tr>
                                <td colspan="3" class="text-end font-bold p-3">Total:</td>
                                <td class="text-end font-bold p-3">{{ number_format($total, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    @endif
                </div>
                <div class="bg-white p-3 text-gray-900 shadow-sm sm:rounded-lg">
                    <h1 class="text-lg font-bold mb-3"><i class="fa fa-shopping-cart"></i> Cart ({{ count($cart) == 0 ?
                        'Empty' : count($cart) . ' Items' }})
                    </h1>
                    @if (count($cart) > 0)
                    <table class="table-auto w-full mb-2">
                        <thead>
                            <tr>
                                <td class="text-start">Subtotal</td>
                                <td class="text-end">{{ number_format($total, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-start">Discount</td>
                                <td class="text-end text-red-600"
                                    x-text="$wire.discount ? '-' + new Intl.NumberFormat().format($wire.discount) : ''">
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <div class="border-b mb-3 py-3" x-data>
                        <h4 class="font-bold">Discount (Rp)</h4>
                        <input class="w-full text-end text-sm border rounded-lg p-2" type="number" name="discount"
                            wire:model.live.defer="discount" x-on:input="$wire.set('discount', $event.target.value)">
                    </div>

                    <div class="bg-slate-100 p-2 rounded-lg">
                        <h4 class="font-bold text-slate-600">Total</h4>
                        <h1 class="font-bold text-2xl text-end"><sup>Rp</sup> {{ number_format($total - $discount, 2) }}
                        </h1>
                    </div>
                    <label for="account">Pembayaran</label>
                    <select class="w-full text-sm border rounded-lg p-2 mb-3" wire:model.live="account" id="account">
                        <option value="">--Pilih Pembayaran--</option>
                        @foreach ($accounts as $account)
                        <option value="{{ $account->acc_code }}">{{ $account->acc_name }}</option>
                        @endforeach
                    </select>
                    <div class="flex justify-end gap-2">
                        <button class="text-white font-bold bg-red-400 py-1 px-3 rounded-lg hover:bg-red-300"
                            wire:click="clearCart">Clear Cart</button>
                        <button class="text-white font-bold bg-blue-400 py-1 px-3 rounded-lg hover:bg-blue-300"
                            wire:click="storeCart">
                            Checkout
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>