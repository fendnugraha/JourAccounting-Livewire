<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto bg-white p-8 shadow-xl rounded-lg">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-4xl font-extrabold text-indigo-600">Invoice</h1>
                    <p class="text-sm text-gray-600">Invoice #: {{ $transaction->first()->invoice }}</p>
                    <p class="text-sm text-gray-400">SN #: {{ $serial }}</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-blue-500">Tanggal: <span class="font-normal text-gray-700">
                            {{ $date_issued }}</span></p>
                    <p class="text-sm text-gray-600">Jatuh Tempo: <span class="font-normal text-gray-700">{{ $due_date
                            }}</span></p>
                </div>
            </div>

            <!-- Company and Client Info Section -->
            <div class="flex justify-between mb-8">
                <!-- From Section -->
                <div class="w-1/2">
                    <h2 class="text-xl font-semibold text-green-600">From</h2>
                    <p class="text-gray-700">{{ $transaction->first()->warehouse->name }}</p>
                    <p class="text-gray-700">{{ $transaction->first()->warehouse->address }}</p>
                </div>

                <!-- To Section -->
                <div class="w-1/2 text-right">
                    <h2 class="text-xl font-semibold text-purple-600">Bill To</h2>
                    <p class="text-gray-700">{{ $transaction->first()->contact->name }}</p>
                </div>
            </div>

            <!-- Table for Invoice Items -->
            <table class="min-w-full table-auto border-collapse mb-8">
                <thead>
                    <tr class="text-left text-sm font-semibold text-gray-700 border-b border-gray-300">
                        <th class="px-6 py-3 text-blue-500 border-r border-gray-300">Description</th>
                        <th class="px-6 py-3 text-blue-500 border-r border-gray-300">Quantity</th>
                        <th class="px-6 py-3 text-blue-500 border-r border-gray-300">Unit Price</th>
                        <th class="px-6 py-3 text-blue-500">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $total = 0;
                    @endphp
                    @foreach ($transaction as $t)
                    @php
                    // Correct the comparison operator here
                    if ($t->transaction_type == 'Sales') {
                    $quantity = -$t->quantity;
                    $price = $t->price;
                    } else {
                    $quantity = $t->quantity;
                    $price = $t->cost;
                    }
                    $total += $quantity * $price;
                    @endphp
                    <tr class="border-b text-sm text-gray-700">
                        <td class="px-6 py-4 border-r border-gray-300">{{ $t->product->name }}</td>
                        <td class="px-6 py-4 text-center border-r border-gray-300">{{ number_format($quantity) }}</td>
                        <td class="px-6 py-4 text-right border-r border-gray-300">{{ number_format($price) }}</td>
                        <td class="px-6 text-right py-4">{{ number_format($quantity * $price) }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

            <!-- Totals Section -->
            <div class="flex justify-between mb-6">
                <div>
                    <p class="text-md font-semibold text-teal-500">Subtotal:</p>
                    <div {{ $serviceFee==0 ? 'hidden' : '' }}>
                        <p class="text-md font-semibold text-teal-500">Biaya Jasa Service (Rp):</p>
                        <small class="text-sm text-gray-600">Teknisi: {{ $transaction->first()->user->name }}</small>
                    </div>
                    <p class="text-md font-semibold text-red-500 mt-2">Discount (Rp):</p>
                    <p class="text-xl font-bold text-orange-500">Total:</p>
                </div>
                <div class="text-right">
                    <p class="text-md font-semibold text-gray-700">{{ number_format($total) }}</p>
                    <div {{ $serviceFee==0 ? 'hidden' : '' }}>
                        <p class="text-md font-semibold text-gray-700">{{ number_format($serviceFee) }}</p>
                    </div>
                    <small>-</small>
                    <p class="text-md font-semibold text-red-700 mt-2">{{ number_format($discount == 0 ? 0 : -$discount)
                        }}
                    </p>
                    <p class="text-2xl border-t font-bold text-orange-500">{{ number_format($total + $serviceFee -
                        $discount) }}
                    </p>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="text-sm text-gray-600">
                <p><strong>Metode Pembayaran:</strong> {{ ($payment_method) }}</p>
                <p {{ $due_date=='Full Payment' ? 'hidden' : '' }}><strong>Notes:</strong> Pembayaran harus dilakukan
                    sebelum jatuh tempo pada tanggal {{ $due_date }},
                    Hubungi Customer Service untuk informasi lebih lanjut.
                </p>
            </div>

            <!-- Footer Section -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>&copy; 2022 {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="{{ route('transaction') }}"
                class="inline-block bg-blue-500 text-white px-6 py-3 rounded-md hover:bg-blue-600 transition duration-300">
                Back to Transactions
            </a>
        </div>
    </div>
</div>