<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-2 mb-4">
                <x-button class="min-w-32" x-data x-on:click="$dispatch('open-modal','create-transfer')">
                    Transfer
                </x-button>
                <x-button class="min-w-32" x-data x-on:click="$dispatch('open-modal','create-withdrawal')">
                    Tarik Tunai
                </x-button>
                <x-dropdown align="left" width="48">
                    <x-slot name="trigger">
                        <x-button class="min-w-32" style="success">
                            Deposit & Voucher
                        </x-button>
                    </x-slot>

                    <x-slot name="content">
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">
                            Profile
                        </a>

                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                            Logout
                        </button>
                    </x-slot>
                </x-dropdown>
                <x-dropdown align="left" width="48">
                    <x-slot name="trigger">
                        <x-button class="min-w-32" style="danger">
                            Pengeluaran (Biaya)
                        </x-button>
                    </x-slot>

                    <x-slot name="content">
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">
                            Profile
                        </a>

                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                            Logout
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
                @livewire('transaction.transaction-table')
                @livewire('transaction.cash-bank-balance')
            </div>
        </div>
    </div>
    <x-modal name="create-transfer" :show="false" :title="'Transfer uang'">
        @livewire('transaction.create-transfer')
    </x-modal>
    <x-modal name="create-withdrawal" :show="false" :title="'Penarikan Tunai'">
        @livewire('transaction.create-withdrawal')
    </x-modal>
</x-app-layout>