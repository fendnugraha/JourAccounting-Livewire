<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
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
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="p-4">Customer / Supplier</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                            <tr class="border-b hover:bg-slate-100">
                                <td class="p-2"><span class="block text-slate-500">{{ $transaction->date_issued }} | {{
                                        $transaction->invoice }}</span>{{ $transaction->contact->name
                                    }}</td>
                                <td>{{ number_format($transaction->total, 2) }}</td>
                                <td class="text-center"><span
                                        class="text-white {{ $transaction->transaction_type == 'Sales' ? 'bg-red-500' : 'bg-green-500' }} py-1 px-2 rounded-lg text-center">{{
                                        $transaction->transaction_type
                                        }}</span></td>
                                <td class="text-center">
                                    <button
                                        class="text-white font-bold bg-amber-400 py-1 px-3 rounded-lg hover:bg-blue-300"
                                        wire:click="show({{ $transaction->id }})">
                                        View
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>