<div class="card p-4 sm:col-span-3 sm:order-0 order-1">
    <h1 class="card-title mb-4">Transaksi
        <span class="card-subtitle">Periode: {{ $startDate }} - {{ $endDate }}</span>
    </h1>
    <div class="flex justify-start items-center mb-2 gap-2">
        <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search .."
            class="w-full border border-slate-300 text-sm rounded-lg p-2" />
        <select wire:model.live="perPage" wire:change="updateLimitPage('journalPage')"
            class="text-sm border border-slate-300 rounded-lg p-2 w-28">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <button wire:click="$refresh" class="text-sm border border-slate-300 rounded-lg p-2 w-12">
            <i class="bi bi-arrow-clockwise text-sm"></i>
        </button>
        <button class="text-sm border border-slate-300 rounded-lg p-2 w-12" x-data
            x-on:click="$dispatch('open-modal','filter-journal')"><i class=" bi bi-funnel"></i>
        </button>
        <x-modal name="filter-journal" :show="false" :title="'Filter'" :maxWidth="'sm'">
            <div class="flex flex-col gap-2">

                @can('admin') <select wire:model.live="warehouse"
                    class="w-full text-sm border border-slate-300 rounded-lg p-2"
                    wire:change="updateLimitPage('journalPage')">
                    <option value="">-- Semua --</option>
                    @foreach ($warehouses as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                @endcan
                <x-input-label for="trx_type" :value="__('Dari')" />
                <x-text-input wire:model.live="startDate" type="date" class="mt-1 block w-full" />
                <x-input-label for="trx_type" :value="__('Sampai')" />
                <x-text-input wire:model.live="endDate" type="date" class="mt-1 block w-full" />
            </div>
        </x-modal>
    </div>
    <select wire:model.live="account" class="w-full text-sm border border-slate-300 rounded-lg p-2">
        <option value="">-- Semua --</option>
        @foreach ($accounts as $ac)
        <option value="{{ $ac->id }}">{{ $ac->acc_name }}</option>
        @endforeach
    </select>
    <div class="overflow-x-auto">
        <table class="table w-full text-sm mb-2">
            <thead class="">
                <tr class="">
                    <th class="">Keterangan</th>
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
                    <td class="">
                        <span class="font-bold text-xs text-sky-700 block">ID: {{ $journal->id
                            }}, {{ $journal->date_issued
                            }}, {{ $journal->invoice
                            }}, {{
                            $journal->trx_type
                            }}</span>
                        <span class="font-bold text-xs text-green-600">{!! ($journal->cred_code == $cash &&
                            $journal->trx_type !==
                            'Mutasi
                            Kas')
                            ? $journal->debt->acc_name
                            : (($journal->debt_code == $cash && $journal->trx_type !== 'Mutasi Kas')
                            ? $journal->cred->acc_name
                            : $journal->cred->acc_name . ' <i class="bi bi-arrow-right"></i> ' .
                            $journal->debt->acc_name)
                            !!}</span>
                        {{ $journal->description }} {{ $journal->sale ? $journal->sale->product->name . ' - ' .
                        $journal->sale->quantity . ' Pcs x Rp' . number_format($journal->sale->price) . '' : '' }}<br>

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
                            <button x-data x-on:click="
                                        $wire.setSelectedId({{ $journal->id }});
                                      $dispatch('open-modal','edit-journal'); " @class(['hidden'=>
                                !in_array($journal->trx_type, ['Transfer Uang', 'Tarik Tunai'])])>
                                <i class="bi bi-pencil text-lg"></i>
                            </button>

                            <button wire:click="destroy({{ $journal->id }})"
                                wire:confirm="Apakah anda yakin menghapus data ini?" wire:loading.attr="disabled"
                                @class(['text-red-500 font-bold disabled:text-slate-300', $hide_pay])>
                                <i class="bi bi-trash text-lg"></i>
                            </button>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $journals->links(data: ['scrollTo' => false]) }}
    </div>
    <x-modal name="edit-journal" :show="false" :title="'Edit Jurnal'">
        @livewire('transaction.edit-journals', ['journal' => $selectedId])
    </x-modal>

</div>