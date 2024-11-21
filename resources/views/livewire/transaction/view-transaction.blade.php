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
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-blue-600">Invoice</h1>
                            <p class="text-gray-500">Invoice #: {{ $transaction->first()->invoice }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold">{{ $transaction->first()->warehouse->name }}</p>
                            <p class="text-gray-500">{{ $transaction->first()->warehouse->address }}</p>
                        </div>
                    </div>

                    <!-- Date and Customer Info -->
                    <div class="flex justify-between mb-8">
                        <div>
                            <p class="text-gray-500">Date: {{ $transaction->first()->created_at }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">Bill To:</p>
                            <p class="text-gray-500">{{ $transaction->first()->contact->name }}</p>
                        </div>
                    </div>

                    <!-- Table: Invoice Items -->
                    <table class="w-full table-auto border-collapse mb-8">
                        <thead>
                            <tr class="bg-gray-200 text-left">
                                <th class="py-2 px-4 border-b font-semibold">Description</th>
                                <th class="py-2 px-4 border-b font-semibold">Quantity</th>
                                <th class="py-2 px-4 border-b font-semibold">Unit Price</th>
                                <th class="py-2 px-4 border-b font-semibold">Total</th>
                                <th class="py-2 px-4 border-b font-semibold"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $total = 0;
                            @endphp
                            @foreach ($transaction as $t)

                            @php
                            $t->transaction_type == 'Sales' ? $quantity = -$t->quantity : $quantity = $t->quantity;
                            $t->transaction_type == 'Sales' ? $amount = $t->price : $amount = $t->cost;
                            $total += $amount * $quantity;
                            @endphp
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $t->product->name }}</td>
                                <td class="py-2 px-4 text-center">{{ number_format($quantity, 2) }}</td>
                                <td class="py-2 px-4 text-center">{{ number_format($amount, 2) }}</td>
                                <td class="py-2 px-4 text-center">{{ number_format($amount * $quantity, 2) }}</td>
                                <td class="py-2 px-4 text-center"><button
                                        wire:confirm="Apakah anda yakin menghapus data ini?"
                                        wire:click="delete({{ $t->id }})"><i class="fa fa-trash"></i></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Total -->
                    <div class="flex justify-end">
                        <div class="w-1/2 text-right">
                            <div class="mb-2">
                                <p class="text-gray-500">Subtotal:</p>
                                <p class="text-gray-500">Discount:</p>
                                <p class="font-semibold text-xl">Total:</p>
                            </div>
                            <div class="border-t pt-2">
                                <p class="font-semibold text-lg"><sup>Rp</sup> {{ number_format($total, 2) }}</p>
                                <p class="font-semibold text-red-400 text-lg">{{ $discountAndFee > 0 ?
                                    '-' . number_format($discountAndFee, 2) : 0 }}</p>
                                </p>
                                <p class="font-bold text-2xl text-blue-600"><sup>Rp</sup> {{ number_format($total -
                                    $discountAndFee,
                                    2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 text-center text-gray-500 text-sm">
                        <p>Thank you for your business!</p>
                        <p>If you have any questions, please contact us at contact@yourcompany.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>