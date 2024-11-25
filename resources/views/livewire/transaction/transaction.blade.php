<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-sub-navlinks :links="[
                        ['href' => route('transaction'), 'route' => 'transaction', 'text' => 'Summary'],
                        ['href' => route('transaction.sales'), 'route' => 'transaction/sales', 'text' => 'Sales'],
                        ['href' => route('transaction.purchases'), 'route' => 'transaction/purchases', 'text' => 'Purchases']
                    ]" />
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-lg font-bold mb-3">{{ __('Transactions Summary') }}</h1>
                    <div class="w-full sm:w-1/2 flex gap-2">
                        <input type="datetime-local" wire:model.live="startDate"
                            class="text-sm border rounded-lg p-2 w-full">
                        <input type="datetime-local" wire:model.live="endDate"
                            class="text-sm border rounded-lg p-2 w-full">
                    </div>
                    <table class="w-full text-sm mb-4">
                        <thead>
                            <tr class="border-b">
                                <th class="text-start p-4">Customer / Supplier</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                            <tr class="border-b hover:bg-slate-100">
                                <td class="p-2"><span class="block text-slate-500 text-xs">{{ $transaction->date_issued
                                        }} | {{
                                        $transaction->invoice }}</span>{{ $transaction->contact->name
                                    }}<br>
                                    <span
                                        class="text-white text-xs {{ $transaction->transaction_type == 'Sales' ? 'bg-red-500' : 'bg-green-500' }} py-1 px-2 rounded-lg text-center">{{
                                        $transaction->transaction_type
                                        }}</span>
                                </td>
                                <td class="text-end p-2">{{ $transaction->transaction_type == 'Sales' ?
                                    number_format($transaction->totalPrice, 2) :
                                    number_format($transaction->totalCost, 2) }}</td>
                                <td class="text-center p-2">
                                    <a href="{{ route('transaction.edit', $transaction->serial_number) }}"
                                        class="text-white text-sm mr-2 font-bold bg-blue-400 py-1 px-3 rounded-lg hover:bg-blue-300">
                                        <i class="fa fa-pen-to-square"></i> Edit
                                    </a>
                                    <a href="{{ route('transaction.view', $transaction->serial_number) }}"
                                        class="text-white text-sm font-bold bg-amber-400 py-1 px-3 rounded-lg hover:bg-amber-300">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $transactions->links() }}

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