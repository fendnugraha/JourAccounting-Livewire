<div>
    <div class="absolute inset-0 flex items-center justify-center" wire:loading>
        <!-- Container for the loading message -->
        <div class="bg-slate-50/10 backdrop-blur-sm h-screen w-screen flex items-center justify-center gap-2">
            <!-- Loading text -->
            <i class="fa-solid fa-spinner animate-spin text-blue-950 text-3xl"></i>
            <p class="text-blue-950 text-sm font-bold">
                Loading data, please wait...
            </p>
        </div>
    </div>
    <div class="flex gap-2 flex-col sm:flex-row items-center mb-2">
        <div class="w-full flex gap-2">
            <input type="datetime-local" wire:model.live="startDate" class="text-sm border rounded-lg p-2 w-full">
            <input type="datetime-local" wire:model.live="endDate" class="text-sm border rounded-lg p-2 w-full">
        </div>
    </div>
    <div class="flex justify-start items-center mb-2 gap-2">
        <select wire:model.live="account" class="w-full text-sm border rounded-lg p-2">
            <option value="">-- Account --</option>
            @foreach ($credits as $ac)
            <option value="{{ $ac->acc_code }}">{{ $ac->acc_name }}</option>
            @endforeach
        </select>
        @can('admin')
        <select wire:model.live="warehouse_id" class="w-1/2 text-sm border rounded-lg p-2"
            wire:change="updateLimitPage('journalPage')">
            <option value="">-- Semua --</option>
            @foreach ($warehouses as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>
        @endcan
        <select wire:model.live="perPage" wire:change="updateLimitPage('journalPage')"
            class="text-sm border rounded-lg p-2 w-40">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-1 sm:gap-2 my-2">
        <div class="bg-gray-600 p-2 sm:px-3 sm:py-2 rounded-xl text-white">
            <h5 class="sm:text-sm">Saldo Awal</h5>
            <span class="sm:text-lg font-bold">{{ Number::format($initBalance) }}</span>
        </div>
        <div class="bg-green-500 p-2 sm:px-3 sm:py-2 rounded-xl text-white">
            <h5 class="sm:text-sm">Debet</h5>
            <span class="sm:text-lg font-bold">{{ Number::format($debt_total) }}</span>
        </div>
        <div class="bg-red-400 p-2 sm:px-3 sm:py-2 rounded-xl text-white">
            <h5 class="sm:text-sm">Credit</h5>
            <span class="sm:text-lg font-bold">{{ Number::format($cred_total) }}</span>
        </div>
        <div class="bg-gray-600 p-2 sm:px-3 sm:py-2 rounded-xl text-white">
            <h5 class="sm:text-sm">Saldo Akhir</h5>
            <span class="sm:text-lg font-bold">{{ Number::format($endBalance) }}</span>
        </div>
    </div>
    <input type="search" wire:model.live.debounce.1500ms="search" placeholder="Search .."
        class="w-full border text-sm rounded-lg p-2" wire:change="updateLimitPage('journalPage')">
    <div class="min-w-full overflow-x-auto">
        <table class="table-auto w-full text-xs mb-2">
            <thead class="bg-white text-blue-950">
                <tr class="border-b-2">
                    <th class="p-4">Keterangan</th>
                    <th class="p-4 text-center">Jumlah</th>
                </tr>
            </thead>

            <tbody class="">
                @foreach ($journals as $journal)
                @php
                $hidden = ($journal->trx_type == 'Accessories' || $journal->trx_type ==
                'Pengeluaran' ||
                $journal->trx_type == 'Mutasi Kas' ||
                $journal->trx_type == 'Purchase' || $journal->trx_type == 'Sales') ||
                ($journal->trx_type == null)
                ? 'hidden' : '';
                $hide_pay = ($journal->trx_type == null || $journal->trx_type == 'Purchase' || $journal->trx_type ==
                'Sales') ? 'hidden' : '';

                @endphp
                <tr
                    class="border-b border-slate-100 odd:bg-white even:bg-blue-50 hover:bg-slate-600 hover:text-white cursor-pointer">
                    <td class="p-2">

                        <span class="font-bold">#{{ $journal->id }} | {{ $journal->date_issued }} |
                            {{ $journal->invoice
                            }} | {{
                            $journal->trx_type
                            }}</span> <br>
                        {{ $journal->description }} {{ $journal->transaction ?
                        $journal->transaction->product->name .
                        ' - ' .
                        $journal->transaction->quantity . ' Pcs x Rp' .
                        number_format($journal->transaction->price) .
                        '' : '' }}<br>
                        <span class="font-bold">{{ ($journal->cred_code == $cash &&
                            $journal->trx_type
                            !== 'Mutasi Kas')
                            ? $journal->debt->acc_name
                            : (($journal->debt_code == $cash && $journal->trx_type !== 'Mutasi Kas')
                            ? $journal->cred->acc_name
                            : $journal->cred->acc_name . ' -> ' . $journal->debt->acc_name)
                            }}</span>
                        <span class="italic font-bold text-slate-600">{{ $journal->status == 2 ?
                            '(Belum
                            diambil)' : ''
                            }}</span><br>
                        <div class="flex justify-evenly gap-1 mt-1 sm:w-1/2">
                            <a href="{{ route('journal.edit', $journal->id) }}"
                                class="text-slate-800 text-center font-bold bg-yellow-400 rounded-md py-1 px-3 w-full hover:bg-yellow-300 {{ $hidden }}"
                                wire:navigate><i class="fa-solid fa-pen-to-square"></i></a>
                            <button wire:click="delete({{ $journal->id }})" wire:loading.attr="disabled"
                                wire:confirm="Apakah anda yakin menghapus data ini?"
                                class="text-white font-bold bg-red-400 rounded-md py-1 px-3 w-full hover:bg-red-300 disabled:bg-slate-300"
                                {{ $hide_pay }}><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                    <td
                        class="p-4 text-md text-right {{ $account == $journal->cred_code ? 'text-red-500' : ($account == $journal->debt_code ? 'text-green-500' : '') }} font-bold p-2">
                        <span class="text-sm sm:text-md">{{ number_format($journal->amount)
                            }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $journals->onEachSide(0)->links(data: ['scrollTo' => false]) }}
    </div>
</div>