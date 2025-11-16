<div class="bg-white rounded-2xl p-4 sm:col-span-3">
    <div class="flex justify-start items-center mb-2 gap-2">
        <select wire:model.live="account" class="w-full text-sm border border-slate-300 rounded-lg p-2">
            <option value="">-- Semua --</option>
            @foreach ($accounts as $ac)
            <option value="{{ $ac->id }}">{{ $ac->acc_name }}</option>
            @endforeach
        </select>
        @can('admin') <select wire:model.live="warehouse" class="w-1/2 text-sm border border-slate-300 rounded-lg p-2"
            wire:change="updateLimitPage('journalPage')">
            <option value="">-- Semua --</option>
            @foreach ($warehouses as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>
        @endcan
        <select wire:model.live="perPage" wire:change="updateLimitPage('journalPage')"
            class="text-sm border border-slate-300 rounded-lg p-2 w-28">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
    <div class="mb-2 flex items-center gap-2">
        <button wire:click="$refresh" class="border border-slate-300 rounded-full p-2 w-11 hover:bg-slate-200">
            <i class="bi bi-arrow-clockwise text-sm"></i>
        </button>
        <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search .."
            class="w-full border border-slate-300 text-sm rounded-lg p-2" />
    </div>
    <div class="overflox-x-auto">
        <table class="table w-full text-sm mb-2">
            <thead class="">
                <tr class="">
                    <th class="p-4">Keterangan</th>
                    <th>Jumlah</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody class="">
                @foreach ($journals as $journal)
                @php
                $hidden = ($journal->trx_type == 'Accessories' || $journal->trx_type == 'Pengeluaran' ||
                $journal->trx_type == 'Mutasi Kas' ||
                $journal->trx_type == 'Voucher & SP' || $journal->trx_type == 'Deposit') || ($journal->trx_type == null)
                ? 'hidden' : '';
                $hide_pay = ($journal->trx_type == null) ? 'disabled' : '';

                @endphp
                <tr class="">
                    <td class="p-2">
                        <span class="font-bold">ID: {{ $journal->id
                            }} | {{ $journal->date_issued
                            }} | {{ $journal->invoice
                            }} | {{
                            $journal->trx_type
                            }}</span> <br>
                        {{ $journal->description }} {{ $journal->sale ? $journal->sale->product->name . ' - ' .
                        $journal->sale->quantity . ' Pcs x Rp' . number_format($journal->sale->price) . '' : '' }}<br>
                        <span class="font-bold">{{ ($journal->cred_code == $cash && $journal->trx_type !== 'Mutasi Kas')
                            ? $journal->debt->acc_name
                            : (($journal->debt_code == $cash && $journal->trx_type !== 'Mutasi Kas')
                            ? $journal->cred->acc_name
                            : $journal->cred->acc_name . ' -> ' . $journal->debt->acc_name)
                            }}</span>
                        <span class="italic font-bold text-slate-600">{{ $journal->status == 2 ? '(Belum diambil)' : ''
                            }}</span><br>
                        <span class="block sm:hidden ">ID:{{ $journal->id }} | {{
                            $journal->date_issued
                            }}</span>
                    </td>
                    <td
                        class="p-1 sm:p-3 text-right {{ $account == $journal->cred_code ? 'text-red-500' : ($account == $journal->debt_code ? 'text-green-500' : '') }} font-bold p-2">
                        <span class="text-sm sm:text-lg font-bold">{{ number_format($journal->amount) }}</span> <br>
                        @if ($journal->fee_amount != 0)
                        <span class="text-sky-600">{{ number_format($journal->fee_amount) }}
                        </span>
                        @endif
                    </td>
                    <td class="">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('journal.edit', $journal->id) }}"
                                class="text-slate-800 text-center font-bold {{ $hidden }}" wire:navigate><i
                                    class="bi bi-pencil text-lg"></i></a>
                            <button wire:click="delete({{ $journal->id }})" wire:loading.attr="disabled"
                                wire:confirm="Apakah anda yakin menghapus data ini?"
                                class="text-red-500 font-bold disabled:text-slate-300" {{ $hide_pay }}><i
                                    class="bi bi-trash text-lg"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $journals->links(data: ['scrollTo' => false]) }}
    </div>
</div>