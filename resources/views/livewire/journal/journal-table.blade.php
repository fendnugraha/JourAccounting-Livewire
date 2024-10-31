<div class="bg-white rounded-lg p-2 mb-3 relative">
    @if(session('success'))
    <x-notification>
        <x-slot name="classes">bg-green-500 text-white absolute bottom-1 left-4 z-50</x-slot>
        <strong>Success!!</strong> {{ session('success') }}
    </x-notification>
    @elseif (session('error'))
    <x-notification>
        <x-slot name="classes">bg-red-500 text-white absolute bottom-1 left-4 z-50</x-slot>
        <strong>Error!!</strong> {{
        session('error') }}
    </x-notification>
    @endif
    <div class="absolute inset-0 flex items-center justify-center" wire:loading>
        <!-- Container for the loading message -->
        <div class="bg-slate-50/10 backdrop-blur-sm h-full w-full flex items-center justify-center gap-2">
            <!-- Loading text -->
            <i class="fa-solid fa-spinner animate-spin text-blue-950 text-3xl"></i>
            <p class="text-blue-950 text-sm font-bold">
                Loading data, please wait...
            </p>
        </div>
    </div>
    <div class="mb-2 flex gap-2 w-full">
        <x-dropdown-button dropdownTitle="Input jurnal" dropdownName="report"
            class="bg-green-600 hover:bg-green-500 text-white sm:py-2 sm:px-6 p-6 text-xl sm:text-sm">
            <div>
                <ul class="text-sm flex flex-col">
                    <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                        <x-modal modalName="voucher" modalTitle="Form Jurnal Umum">
                            <livewire:journal.create-journal />
                        </x-modal>
                        <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'voucher'})"
                            class="w-full text-left">
                            Jurnal Umum
                        </button>
                    </li>
                    <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                        <x-modal modalName="deposit" modalTitle="Form Penjualan Deposit">
                            <livewire:journal.create-deposit />
                        </x-modal>
                        <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'deposit'})"
                            class="w-full text-left">
                            Deposit
                        </button>
                    </li>
                    <li class="py-2 px-4 hover:bg-slate-100 transition">
                        <x-modal modalName="salesValue" modalTitle="Form Penjualan (Value)">
                            {{--
                            <livewire:journal.create-salesValue /> --}}
                        </x-modal>
                        <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'salesValue'})"
                            class="w-full text-left">
                            Penjualan (Value)
                        </button>
                    </li>
                </ul>
            </div>
        </x-dropdown-button>
        <x-dropdown-button dropdownTitle="Finance" dropdownName="report"
            class="bg-green-600 hover:bg-green-500 text-white sm:py-2 sm:px-6 p-6 text-xl sm:text-sm">
            <div>
                <ul class="text-sm flex flex-col">
                    <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                        <x-modal modalName="voucher" modalTitle="Form Penjualan Voucher">
                            {{--
                            <livewire:journal.create-voucher /> --}}
                        </x-modal>
                        <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'voucher'})"
                            class="w-full text-left">
                            Hutang
                        </button>
                    </li>
                    <li class="py-2 px-4 hover:bg-slate-100 transition">
                        <x-modal modalName="deposit" modalTitle="Form Penjualan Deposit">
                            {{--
                            <livewire:journal.create-deposit /> --}}
                        </x-modal>
                        <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'deposit'})"
                            class="w-full text-left">
                            Piutang
                        </button>
                    </li>
                </ul>
            </div>
        </x-dropdown-button>
        <button wire:click="$refresh"
            class="bg-sky-950 text-white px-3 py-1 text-sm shadow-300 justify-center items-center rounded-full hover:bg-sky-800 transition duration-300 ease-out"><i
                class="fa-solid fa-arrows-rotate"></i>
        </button>
    </div>
    <div class="flex gap-2 flex-col sm:flex-row items-center mb-2">
        <div class="w-full flex gap-2">
            <input type="datetime-local" wire:model.live="startDate" class="text-sm border rounded-lg p-2 w-full">
            <input type="datetime-local" wire:model.live="endDate" class="text-sm border rounded-lg p-2 w-full">
        </div>
        <div class="w-full flex gap-2">
            <select wire:model.live="account" class="w-full text-sm border rounded-lg p-2">
                <option value="">-- Account --</option>
                @foreach ($credits as $ac)
                <option value="{{ $ac->acc_code }}">{{ $ac->acc_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="flex justify-start items-center mb-2 gap-2">
        <input type="search" wire:model.live.debounce.1500ms="search" placeholder="Search .."
            class="w-full border text-sm rounded-lg p-2" wire:change="updateLimitPage('journalPage')">
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
        <div class="bg-sky-700 p-2 sm:px-3 sm:py-1 rounded-xl text-white">
            <h5 class="sm:text-sm">Saldo Awal</h5>
            <span class="sm:text-lg font-bold">{{ Number::format($initBalance) }}</span>
        </div>
        <div class="bg-green-500 p-2 sm:px-3 sm:py-1 rounded-xl text-white">
            <h5 class="sm:text-sm">Debet</h5>
            <span class="sm:text-lg font-bold">{{ Number::format($debt_total) }}</span>
        </div>
        <div class="bg-red-500 p-2 sm:px-3 sm:py-1 rounded-xl text-white">
            <h5 class="sm:text-sm">Credit</h5>
            <span class="sm:text-lg font-bold">{{ Number::format($cred_total) }}</span>
        </div>
        <div class="bg-sky-700 p-2 sm:px-3 sm:py-1 rounded-xl text-white">
            <h5 class="sm:text-sm">Saldo Akhir</h5>
            <span class="sm:text-lg font-bold">{{ Number::format($endBalance) }}</span>
        </div>
    </div>
    <div class="min-w-full overflow-x-auto">
        <table class="table-auto w-full text-xs mb-2">
            <thead class="bg-white text-blue-950">
                <tr class="border-b-2">
                    <th class="p-4 hidden sm:table-cell">ID</th>
                    <th class="p-4 hidden sm:table-cell">Waktu</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
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
                <tr
                    class="border-b border-slate-100 odd:bg-white even:bg-blue-50 hover:bg-slate-600 hover:text-white cursor-pointer">
                    <td class="p-2 hidden sm:table-cell">{{ $journal->id }}</td>
                    <td class="p-2 hidden sm:table-cell">{{ $journal->date_issued }}</td>
                    <td class="p-2">

                        <span class="font-bold">{{ $journal->invoice
                            }} | {{
                            $journal->trx_type
                            }}</span> <br>
                        {{ $journal->description }} {{ $journal->sale ? $journal->sale->product->name . ' - ' .
                        $journal->sale->quantity . ' Pcs x Rp' . number_format($journal->sale->price) . '' : '' }}<br>
                        <span class="font-bold text-slate-600">{{ $journal->cred->acc_name . ' -> ' .
                            $journal->debt->acc_name }}</span>
                        <span class="italic font-bold text-slate-600">{{ $journal->status == 2 ? '(Belum diambil)' : ''
                            }}</span><br>
                        <span class="block sm:hidden ">ID:{{ $journal->id }} | {{
                            $journal->date_issued
                            }}</span>
                        <div class="flex justify-evenly gap-1 mt-1 sm:w-1/2">
                            {{-- <a href="{{ route('journal.edit', $journal->id) }}"
                                class="text-slate-800 text-center font-bold bg-yellow-400 rounded-md py-1 px-3 w-full hover:bg-yellow-300 {{ $hidden }}"
                                wire:navigate><i class="fa-solid fa-pen-to-square"></i></a> --}}
                            <button wire:click="delete({{ $journal->id }})" wire:loading.attr="disabled"
                                wire:confirm="Apakah anda yakin menghapus data ini?"
                                class="text-white font-bold bg-red-400 rounded-md py-1 px-3 w-full hover:bg-red-300 disabled:bg-slate-300"
                                {{ $hide_pay }}><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                    <td
                        class="text-right {{ $account == $journal->cred_code ? 'text-red-500' : ($account == $journal->debt_code ? 'text-green-500' : '') }} font-bold p-2">
                        <span class="text-sm sm:text-xs">{{ number_format($journal->amount) }}</span> <br>
                        <span class="sm:hidden text-sky-600">{{ number_format($journal->fee_amount) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $journals->onEachSide(0)->links(data: ['scrollTo' => false]) }}
    </div>
</div>