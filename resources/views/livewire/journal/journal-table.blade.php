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
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-1 sm:gap-3 mb-4">
        <div class="bg-gray-600 p-2 sm:px-4 sm:py-6 rounded-3xl text-white">
            <span class="sm:text-4xl font-bold">{{ Number::format($initBalance) }}</span>
            <h5 class="sm:text-sm">Saldo Awal</h5>
        </div>
        <div class="bg-gray-600 p-2 sm:px-4 sm:py-6 rounded-3xl text-white">
            <span class="sm:text-4xl font-bold">{{ Number::format($debt_total) }}</span>
            <h5 class="sm:text-sm">Debet</h5>
        </div>
        <div class="bg-gray-600 p-2 sm:px-4 sm:py-6 rounded-3xl text-white">
            <span class="sm:text-4xl font-bold">{{ Number::format($cred_total) }}</span>
            <h5 class="sm:text-sm">Credit</h5>
        </div>
        <div class="bg-gray-600 p-2 sm:px-4 sm:py-6 rounded-3xl text-white">
            <span class="sm:text-4xl font-bold">{{ Number::format($endBalance) }}</span>
            <h5 class="sm:text-sm">Saldo Akhir</h5>
        </div>
    </div>
    <div class="mb-2 flex gap-2">
        <x-dropdown-button dropdownTitle="Input Jurnal" dropdownName="report"
            class="bg-blue-500 text-white min-w-36 sm:py-3 sm:px-8 p-6 text-xl sm:text-sm hover:shadow-md flex justify-center items-center rounded-xl hover:bg-blue-600 transition duration-300 ease-out">
            <div>
                <ul class="text-sm flex flex-col">
                    <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                        <x-modal modalName="createIncome" modalTitle="Form Input Kas Masuk (Income)">
                            <livewire:journal.create-income />
                        </x-modal>
                        <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'createIncome'})"
                            class="w-full text-left">
                            Kas Masuk (Income)
                        </button>
                    </li>
                    <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                        <x-modal modalName="createExpense" modalTitle="Form Input Kas Keluar (Expense)">
                            <livewire:journal.create-expense />
                        </x-modal>
                        <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'createExpense'})"
                            class="w-full text-left">
                            Kas Keluar (Expense)
                        </button>
                    </li>
                    <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                        <x-modal modalName="createMutation" modalTitle="Form Input Mutasi">
                            <livewire:journal.create-mutation />
                        </x-modal>
                        <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'createMutation'})"
                            class="w-full text-left">
                            Mutaasi Kas & Bank
                        </button>
                    </li>
                </ul>
            </div>
        </x-dropdown-button>
        <x-dropdown-button dropdownTitle="Finance" dropdownName="report"
            class="bg-blue-500 text-white min-w-36 sm:py-3 sm:px-8 p-6 text-xl sm:text-sm hover:shadow-md flex justify-center items-center rounded-xl hover:bg-blue-600 transition duration-300 ease-out">
            <div>
                <ul class="text-sm flex flex-col">
                    <a href="{{ route('finance.receivable') }}" wire:navigate>
                        <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                            Piutang
                        </li>
                    </a>
                    <a href="{{ route('finance.payable') }}" wire:navigate>
                        <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                            Hutang
                        </li>
                    </a>
                </ul>
            </div>
        </x-dropdown-button>
    </div>
    <div class="p-4 bg-white rounded-2xl">
        <div class="flex gap-2 flex-col sm:flex-row items-center mb-2">
            <div class="w-full sm:w-1/2 flex gap-2">
                <input type="datetime-local" wire:model.live="startDate" class="text-sm border rounded-lg p-2 w-full">
                <input type="datetime-local" wire:model.live="endDate" class="text-sm border rounded-lg p-2 w-full">
            </div>
            <select wire:model.live="account" class="w-full sm:w-1/2 text-sm border rounded-lg p-2">
                <option value="">-- Account --</option>
                @foreach ($credits as $ac)
                <option value="{{ $ac->acc_code }}">{{ $ac->acc_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-5 gap-2 mb-2">
            <input type="search" wire:model.live.debounce.1500ms="search" placeholder="Search .."
                class="w-full col-span-4 border text-sm rounded-lg p-2" wire:change="updatePerPage('journalPage')">
            @can('admin')
            <select wire:model.live="warehouse_id" class="w-1/2 text-sm border rounded-lg p-2"
                wire:change="updatePerPage('journalPage')">
                <option value="">-- Semua --</option>
                @foreach ($warehouses as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
            @endcan
            <select wire:model.live="perPage" wire:change="updatePerPage('journalPage')"
                class="w-full text-sm border rounded-lg p-2">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="min-w-full overflow-x-auto">
            <table class="table-auto w-full text-xs sm:text-sm mb-2">
                <thead class="bg-white text-blue-950">
                    <tr class="border-b-2">
                        <th class="p-4">Keterangan</th>
                        <th class="p-4 text-center">Jumlah</th>
                    </tr>
                </thead>

                <tbody class="">
                    @foreach ($journals as $journal)
                    @php
                    $hidden = in_array($journal->trx_type, [null, 'Mutasi Kas', 'Accessories',
                    'Purchase',
                    'Sales', 'Payable', 'Receivable']) ?
                    'hidden' : '';
                    $hide_pay = in_array($journal->trx_type, [null, 'Purchase', 'Sales', 'Payable', 'Receivable']) ?
                    'hidden' : '';

                    @endphp
                    <tr class="border-b border-slate-200 hover:bg-slate-600 hover:text-white cursor-pointer">
                        <td class="p-2">
                            <span class="font-bold text-primary">#{{ $journal->id }} | {{ $journal->date_issued }} |
                                {{ $journal->invoice
                                }} | {{
                                $journal->trx_type
                                }}</span>
                            <a href="{{ route('journal.edit', $journal->id) }}"
                                class="mx-1 text-yellow-600 hover:text-yellow-400 {{ $hidden }}" wire:navigate><i
                                    class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <a role="button" wire:click="delete({{ $journal->id }})" wire:loading.attr="disabled"
                                wire:confirm="Apakah anda yakin menghapus data ini?"
                                class=" text-red-600 hover:text-red-400 {{ $hidden }}"><i class="fa-solid fa-trash"></i>
                                Hapus</a>
                            <br>

                            <span class="font-bold">{{ ($journal->cred_code == $cash &&
                                $journal->trx_type
                                !== 'Mutasi Kas')
                                ? $journal->debt->acc_name
                                : (($journal->debt_code == $cash && $journal->trx_type !== 'Mutasi Kas')
                                ? $journal->cred->acc_name
                                : $journal->cred->acc_name . ' -> ' . $journal->debt->acc_name)
                                }}</span><br>
                            <span class="text-xs">Note: {{ $journal->description }}</span>
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
</div>