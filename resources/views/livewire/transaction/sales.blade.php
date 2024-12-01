<div class="">
    <x-modal modalName="createCntact" modalTitle="Form Tambah Contact">
        <livewire:setting.contact.create-contact />
    </x-modal>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>
    @if(session('success'))
    <x-notification class="bg-green-500 text-white mb-3">
        <div>
            <strong class="text-sm"><i class="fas fa-check-circle"></i> Success!!</strong>
            <h4 class="text-xs">{{ session('success') ?? 'Input berhasil ditambahkan' }}</h4>
        </div>
    </x-notification>
    @elseif (session('error'))
    <x-notification class="bg-red-500 text-white mb-3">
        <div>
            <strong class="text-sm"><i class="fas fa-times-circle"></i> Error!!</strong>
            <h4 class="text-xs">{{ session('error') ?? 'Terjadi kesalahan, silahkan coba lagi' }}</h4>
        </div>
    </x-notification>
    @endif

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-sub-navlinks :links="[
                ['href' => route('transaction'), 'route' => 'transaction', 'text' => 'Summary'],
                ['href' => route('transaction.sales'), 'route' => 'transaction.sales', 'text' => 'Sales'],
                ['href' => route('transaction.purchases'), 'route' => 'transaction.purchases', 'text' => 'Purchases']
            ]" />

            <div class="grid sm:grid-cols-4 grid-cols-1 gap-2 pb-10 sm:pb-0">
                <div class="bg-white p-3 text-gray-900 shadow-sm sm:rounded-lg col-span-3">
                    {{-- <h1 class="text-lg font-bold mb-3">{{ __('Sales Order') }}</h1> --}}
                    <div class="relative z-50" x-data="{ showResults: false }">
                        <div class="mb-2 grid grid-cols-4 gap-2 items-center">
                            <label class="" for="contact_id">Customer</label>
                            <select
                                class="w-full text-sm border rounded-lg p-2 col-span-2 @error('contact_id') border-red-500 @enderror"
                                wire:model="contact_id" id="contact_id">
                                <option value="">--Pilih Customer--</option>
                                @foreach ($contacts as $contact)
                                <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                @endforeach
                            </select>
                            <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'createCntact'})"
                                class="bg-red-400 text-white min-w-36 border sm:py-2 sm:px-8 p-6 text-xl sm:text-sm hover:shadow-md flex justify-center items-center rounded-xl hover:bg-red-500 transition duration-300 ease-out">
                                Add Contact &nbsp; <i class="fa-solid fa-plus-circle"></i>
                            </button>
                        </div>
                        @error('contact_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                        <div x-on:click.away="showResults = false">
                            <input type="search" class="w-full text-sm border rounded-lg p-2 mb-2"
                                wire:model.live.debounce.500ms="search" x-on:focus="showResults = true"
                                x-on:input="showResults = true" placeholder="Search item..">

                            <!-- Display search results only when `showResults` is true -->
                            <div class="absolute top-18 left-0 w-full sm:w-3/4 bg-white border border-gray-200 rounded-lg shadow-lg"
                                x-show="showResults" x-transition>
                                <table class="table-auto text-sm w-full">
                                    <tbody>
                                        @foreach ($products as $product)
                                        <tr class="border-b hover:bg-yellow-100">
                                            <td class="p-2">{{ $product->name }}</td>
                                            <td class="p-2">{{ $product->end_stock }} <sup>Pcs</sup></td>
                                            <td>{{ number_format($product->price, 2) }}</td>
                                            <td>
                                                <button type="button"
                                                    class="text-white font-bold bg-green-400 py-1 px-3 rounded-lg hover:bg-green-300"
                                                    wire:click="addToCart({{ $product->id }})">
                                                    <i class="fa fa-plus-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if (session('salesCart'))
                    <table class="table-auto w-full text-xs my-2">
                        {{-- <thead class="bg-white text-blue-950">
                            <tr>
                                <th class="text-start">Quantity</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead> --}}
                        <tbody class="bg-white">
                            @php
                            $total = 0;
                            @endphp
                            @foreach ($salesCart as $item)
                            @php
                            // Calculate subtotal for the current item
                            $subtotal = $item['qty'] * $item['price'];
                            // Add to the total
                            $total += $subtotal;
                            @endphp
                            <tr>
                                <td colspan="2" class="text-lg py-2">{{ $item['name'] }}</td>
                                <td class="text-end py-2">
                                    <button
                                        class="text-white font-bold bg-red-400 py-1 px-3 rounded-lg hover:bg-red-300"
                                        wire:click="removeFromCart({{ $item['id'] }})">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-b border-slate-200">
                                <td>
                                    <input type="number"
                                        class="w-full text-end text-xs rounded-lg bg-slate-300 border-0"
                                        wire:change="updateQty({{ $item['id'] }}, $event.target.value)"
                                        wire:model="salesCart.{{ $item['id'] }}.qty">
                                    <span class="font-bold text-blue-900 text-sm">Qty</span>
                                </td>
                                <td colspan="2" class="ps-2 text-end">
                                    <input type="number"
                                        class="w-full text-end text-xs rounded-lg bg-slate-300 border-0"
                                        wire:change="updateCost({{ $item['id'] }}, $event.target.value)"
                                        wire:model="salesCart.{{ $item['id'] }}.price">
                                    Subtotal: <span class="font-bold text-blue-900 text-sm">{{ number_format($subtotal,
                                        2)
                                        }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-100">
                            <tr>
                                <td class="text-end font-bold p-3">Total:</td>
                                <td colspan="2" class="text-end font-bold p-3">{{ number_format($total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    @endif
                </div>
                <div class="bg-white p-3 text-gray-900 shadow-sm sm:rounded-lg hidden sm:block">
                    <h1 class="text-lg font-bold mb-3"><i class="fa fa-shopping-cart"></i> Cart ({{ count($salesCart) ==
                        0 ?
                        'Empty' : count($salesCart) . ' Items' }})
                    </h1>
                    @if (count($salesCart) > 0)
                    <table class="table-auto w-full mb-2">
                        <thead>
                            <tr>
                                <td class="text-start">Subtotal</td>
                                <td class="text-end">{{ number_format($total, 2) }}</td>
                            </tr>
                            @if ($serviceFee)
                            <tr>
                                <td class="text-start">Service Fee</td>
                                <td class="text-end"
                                    x-text="$wire.serviceFee ? new Intl.NumberFormat().format($wire.serviceFee) : ''">
                                </td>
                            </tr>
                            @endif
                            @if ($discount)
                            <tr>
                                <td class="text-start">Discount</td>
                                <td class="text-end text-red-600"
                                    x-text="$wire.discount ? '-' + new Intl.NumberFormat().format($wire.discount) : ''">
                                </td>
                            </tr>
                            @endif
                        </thead>
                    </table>
                    <div class="border-b mb-3 py-3" x-data>
                        <h4 class="font-bold">Discount (Rp)</h4>
                        <input class="w-full text-end text-sm border rounded-lg p-2" type="number" name="discount"
                            wire:model.live.defer="discount" x-on:input="$wire.set('discount', $event.target.value)">
                    </div>

                    <div class="bg-slate-100 p-2 rounded-lg">
                        <h4 class="font-bold text-slate-600">Total</h4>
                        <h1 class="font-bold text-2xl text-end"><sup>Rp</sup> {{ number_format($total + $serviceFee -
                            $discount, 2) }}
                        </h1>
                    </div>
                    <div class="mb-2">
                        <label class="font-bold">Jasa Service</label>
                        <input class="w-full text-end text-sm border rounded-lg p-2" type="number" name="serviceFee"
                            wire:model.live.defer="serviceFee"
                            x-on:input="$wire.set('serviceFee', $event.target.value)">
                    </div>
                    <div class="mb-2" x-show="$wire.payment_method === 'Cash'">
                        <label for="account">Pembayaran</label>
                        <select class="w-full text-sm border rounded-lg p-2 @error('account') border-red-500 @enderror"
                            wire:model.live="account" id="account">
                            <option value="">--Pilih Account--</option>
                            @foreach ($accounts as $account)
                            <option value="{{ $account->acc_code }}">{{ $account->acc_name }}</option>
                            @endforeach
                        </select>
                        @error('account')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-2" x-show="$wire.payment_method === 'Credit'">
                        <label for="dueDate">Jatuh Tempo (Hari)</label>
                        <input type="number" wire:model.defer="dueDate" class="w-full text-sm border rounded-lg p-2"
                            placeholder="Default: 30" min="1">
                        @error('dueDate')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <input type="radio" wire:model.live="payment_method" value="Cash" id="cash"
                                x-click="showPayment = true">
                            <label for="Cash">Cash</label>
                        </div>
                        <div>
                            <input type="radio" wire:model.live="payment_method" value="Credit" id="credit"
                                x-click="showPayment = false">
                            <label for="Credit">Credit</label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button class="text-white font-bold bg-red-400 py-2 px-3 rounded-2xl hover:bg-red-300"
                            wire:click="clearCart">Clear Cart</button>
                        <button class="text-white font-bold bg-gray-700 py-2 px-3 rounded-2xl hover:bg-gray-600"
                            wire:click="storeCart">
                            Checkout
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile --}}
    @if (count($salesCart) > 0)
    <div class="fixed sm:hidden bottom-0 w-full p-4 z-[99]" x-data="{ showCart: false }">
        <!-- Cart Details Section -->
        <div class="bg-yellow-200 p-3 text-gray-900 shadow-sm mb-3 rounded-2xl" x-show="showCart" x-transition
            x-on:click.away="showCart = false">
            <table class="table-auto w-full mb-2">
                <thead>
                    <tr>
                        <td class="text-start">Subtotal</td>
                        <td class="text-end">{{ number_format($total, 2) }}</td>
                    </tr>
                    @if ($serviceFee)
                    <tr>
                        <td class="text-start">Service Fee</td>
                        <td class="text-end"
                            x-text="$wire.serviceFee ? new Intl.NumberFormat().format($wire.serviceFee) : ''">
                        </td>
                    </tr>
                    @endif
                    @if ($discount)
                    <tr>
                        <td class="text-start">Discount</td>
                        <td class="text-end text-red-600"
                            x-text="$wire.discount ? '-' + new Intl.NumberFormat().format($wire.discount) : ''">
                        </td>
                    </tr>
                    @endif
                </thead>
            </table>
            <div class="border-b border-yellow-300 mb-3 py-3">
                <label class="font-bold">Discount (Rp)</label>
                <input class="w-full text-end text-sm border rounded-lg p-2" type="number" name="discount"
                    wire:model.live="discount">
            </div>
            <div class="mb-2">
                <label class="font-bold">Jasa Service</label>
                <input class="w-full text-end text-sm border rounded-lg p-2" type="number" name="serviceFee"
                    wire:model.live="serviceFee">
            </div>
            <div class="mb-3 ">
                <label for="account">Pembayaran</label>
                <select class="w-full text-sm border rounded-lg p-2 @error('account') border-red-500 
            @enderror" wire:model.live="account" id="account">
                    <option value="">--Pilih Account--</option>
                    @foreach ($accounts as $account)
                    <option value="{{ $account->acc_code }}">{{ $account->acc_name }}</option>
                    @endforeach
                </select>
                @error('account') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="grid grid-cols-2 gap-2">
                <button class="text-white font-bold bg-red-400 py-3 px-3 rounded-2xl hover:bg-red-300"
                    wire:click="clearCart" wire:confirm="Apakah anda yakin menghapus data ini?"><i
                        class="fa fa-trash"></i>
                    Clear Cart</button>
                <button class="text-white font-bold bg-gray-700 py-3 px-3 rounded-2xl hover:bg-gray-600"
                    wire:click="storeCart">
                    <i class="fa fa-check"></i> Checkout
                </button>
            </div>
        </div>

        <!-- Toggle Cart Section -->
        <div class="bg-gray-800 hover:bg-gray-700 text-white p-3 shadow-sm rounded-2xl flex justify-between items-center cursor-pointer"
            x-on:click="showCart = !showCart">
            <h1 class="text-xl">
                <i class="fa fa-shopping-cart"></i>
                {!! count($salesCart) === 0 ? 'Empty' : '<span class="font-bold text-yellow-400">' . count($salesCart) .
                    '</span> Items' !!}
            </h1>
            <div>
                <h4 class="text-xs">Total</h4>
                <h4 class="font-bold text-xl">{{ number_format($total + $serviceFee - $discount, 2) }}</h4>
            </div>
        </div>
    </div>
    @endif

    {{-- End Mobile --}}
</div>