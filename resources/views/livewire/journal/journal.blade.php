<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex gap-2">
                        <x-modal modalName="createJournal" modalTitle="Input Jurnal Umum">
                            <livewire:journal.create-journal />
                        </x-modal>
                        <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'createJournal'})"
                            class="bg-sky-700 text-white min-w-36 sm:py-2 sm:px-8 p-6 text-xl sm:text-sm shadow-md flex justify-center items-center rounded-xl hover:bg-sky-800 transition duration-300 ease-out">
                            <i class="fa-solid fa-file-pen"></i> &nbsp; Input jurnal
                        </button>
                        <x-dropdown-button dropdownTitle="Voucher & Deposit" dropdownName="report"
                            class="bg-sky-700 text-white min-w-36 sm:py-2 sm:px-8 p-6 text-xl sm:text-sm shadow-md flex justify-center items-center rounded-xl hover:bg-sky-800 transition duration-300 ease-out">
                            <div>
                                <ul class="text-sm flex flex-col">
                                    <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                                        <x-modal modalName="voucher" modalTitle="Form Penjualan Voucher">
                                            <livewire:journal.create-journal />
                                        </x-modal>
                                        <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'voucher'})"
                                            class="w-full text-left">
                                            Voucher & SP
                                        </button>
                                    </li>
                                    <li class="py-2 px-4 hover:bg-slate-100 transition">
                                        <x-modal modalName="deposit" modalTitle="Form Penjualan Deposit">
                                            <livewire:journal.create-journal />
                                        </x-modal>
                                        <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'deposit'})"
                                            class="w-full text-left">
                                            Deposit (Pulsa dll)
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </x-dropdown-button>
                    </div>
                    @livewire('journal.journal-table')
                </div>
            </div>
        </div>
    </div>
</div>
</div>