<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>
    <x-modal modalName="createCntact" modalTitle="Form Tambah Contact">
        <livewire:setting.contact.create-contact />
    </x-modal>
    <x-modal modalName="createReceivablePayment" modalTitle="Form Input Pembayaran Piutang">
        <livewire:finance.receivable.create-receivable-payment />
    </x-modal>
    <x-modal modalName="createreceivable" modalTitle="Form Input Piutang">
        <livewire:finance.receivable.create-receivable />
    </x-modal>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-5 gap-3">
                    <div class="bg-white p-6 text-gray-900 rounded-2xl col-span-3 shadow-md">
                        <div class="mb-6 flex justify-between gap-2">
                            <div class="flex gap-2">
                                <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'createreceivable'})"
                                    class="bg-blue-500 text-white min-w-36 sm:py-3 sm:px-8 p-6 text-xl sm:text-sm hover:shadow-md flex justify-center items-center rounded-xl hover:bg-blue-600 transition duration-300 ease-out">
                                    Input Piutang &nbsp; <i class="fa-solid fa-plus-circle"></i>
                                </button>

                                <button x-data
                                    x-on:click="$dispatch('open-modal', {'modalName': 'createReceivablePayment'})"
                                    class="bg-blue-500 text-white min-w-36 sm:py-3 sm:px-8 p-6 text-xl sm:text-sm hover:shadow-md flex justify-center items-center rounded-xl hover:bg-blue-600 transition duration-300 ease-out">
                                    Input Pembayaran &nbsp; <i class="fa-solid fa-plus-circle"></i>
                                </button>
                            </div>
                            <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'createCntact'})"
                                class="bg-red-400 text-white min-w-36 sm:py-3 sm:px-8 p-6 text-xl sm:text-sm hover:shadow-md flex justify-center items-center rounded-xl hover:bg-red-500 transition duration-300 ease-out">
                                Add Contact &nbsp; <i class="fa-solid fa-plus-circle"></i>
                            </button>
                        </div>
                        <div class="mb-2">
                            <input type="search" class="w-full p-2 border rounded-xl"
                                placeholder="Search invoice number .." wire:model.live.debounce.500ms="searchInvoice">
                        </div>
                        @if(session('success'))
                        <span>{{ session('success') }}</span>
                        @elseif (session('error'))
                        <span>{{ session('error') }}</span>
                        @endif
                        <table class="table-auto w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="p-4 text-start">Description</th>
                                    <th class="p-4">Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                use Carbon\Carbon;
                                @endphp
                                @foreach ($receivables as $p)
                                @php
                                $today = Carbon::parse(now());
                                $dueDate = Carbon::parse($p->due_date);

                                $diff = round($today->diffInDays($dueDate));
                                if($p->journals->first()->trx_type == 'Sales' && $p->payment_nth == 0){
                                $hide = 'hidden';
                                }
                                @endphp
                                <tr class="border-b hover:bg-slate-50 hover:border-white">
                                    <td class="p-2">
                                        <span class="text-xs font-bold">{{ $p->date_issued }} | {{ $p->invoice }} | {{
                                            $p->journals->first()->trx_type }}</span>
                                        <br>
                                        Contact: <span class="text-sm text-blue-600 font-bold">{{ $p->contact->name
                                            }}</span>
                                        <br>
                                        Note: {{ $p->description }}
                                        <br>
                                        <span class="text-xs text-slate-500">
                                            {{ $p->payment_status == 1 ? 'Completed' : 'Jatuh tempo ' . $p->due_date . '
                                            (' . $diff . ' hari lagi)' }}
                                        </span>

                                    </td>
                                    <td
                                        class="text-end p-2 font-bold text-md {{ $p->bill_amount > 0 ? 'text-green-500' : 'text-red-500' }}">
                                        {{ number_format($p->bill_amount > 0 ? $p->bill_amount : $p->payment_amount) }}
                                    </td>
                                    <td class="text-center">
                                        <button type="button" wire:click="delete({{ $p->id }})"
                                            class="px-3 py-2 rounded-xl bg-red-300 hover:bg-red-400 text-white text-xs"
                                            wire:confirm="are you sure?" {{ $hide ?? '' }}>Delete</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $receivables->links() }}
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div class="bg-blue-800 text-white p-3 rounded-2xl mb-3 shadow-md">
                            <h4 class="text-sm">Total Piutang</h4>
                            <h1 class="font-bold text-4xl"><sup class="text-xs">Rp. </sup>{{
                                number_format($total) }}</h1>
                        </div>
                        <div class="bg-white p-6 text-gray-900 rounded-2xl shadow-md">
                            <div class="mb-2">
                                <input type="search" class="w-full p-2 border rounded-xl" placeholder="Search .."
                                    wire:model.live.debounce.500ms="search">
                            </div>
                            <table class="table-auto w-full text-sm">
                                <thead>
                                    <tr class="border-b">
                                        <th class="p-4">Contact</th>
                                        <th class="p-4">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($receivableContact as $pc)
                                    <tr class="border-b hover:bg-slate-50">
                                        <td class="-p2">{{ $pc->contact->name }}</td>
                                        <td class="text-end p-2 font-bold">{{ number_format($pc->total) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $receivableContact->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>