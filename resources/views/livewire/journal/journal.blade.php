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
                        <x-dropdown-button dropdownTitle="Input Jurnal" dropdownName="report"
                            class="bg-sky-700 text-white min-w-36 sm:py-2 sm:px-8 p-6 text-xl sm:text-sm shadow-md flex justify-center items-center rounded-xl hover:bg-sky-800 transition duration-300 ease-out">
                            <div>
                                <ul class="text-sm flex flex-col">
                                    <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                                        <x-modal modalName="createIncome" modalTitle="Form Input Kas Masuk (Income)">
                                            <livewire:journal.create-income />
                                        </x-modal>
                                        <button x-data
                                            x-on:click="$dispatch('open-modal', {'modalName': 'createIncome'})"
                                            class="w-full text-left">
                                            Kas Masuk (Income)
                                        </button>
                                    </li>
                                    <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                                        <x-modal modalName="createExpense" modalTitle="Form Input Kas Keluar (Expense)">
                                            <livewire:journal.create-expense />
                                        </x-modal>
                                        <button x-data
                                            x-on:click="$dispatch('open-modal', {'modalName': 'createExpense'})"
                                            class="w-full text-left">
                                            Kas Keluar (Expense)
                                        </button>
                                    </li>
                                    <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                                        <x-modal modalName="createMutation" modalTitle="Form Input Mutasi">
                                            <livewire:journal.create-mutation />
                                        </x-modal>
                                        <button x-data
                                            x-on:click="$dispatch('open-modal', {'modalName': 'createMutation'})"
                                            class="w-full text-left">
                                            Mutaasi Kas & Bank
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </x-dropdown-button>
                        <x-dropdown-button dropdownTitle="Finance" dropdownName="report"
                            class="bg-sky-700 text-white min-w-36 sm:py-2 sm:px-8 p-6 text-xl sm:text-sm shadow-md flex justify-center items-center rounded-xl hover:bg-sky-800 transition duration-300 ease-out">
                            <div>
                                <ul class="text-sm flex flex-col">
                                    <a href="{{ route('finance.receivable') }}">
                                        <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                                            Piutang
                                        </li>
                                    </a>
                                    <a href="{{ route('finance.payable') }}">
                                        <li class="py-2 px-4 hover:bg-slate-100 transition border-b">
                                            Hutang
                                        </li>
                                    </a>
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