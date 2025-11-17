<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="hidden sm:flex gap-2 mb-4">
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
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100" x-data
                            x-on:click="$dispatch('open-modal','create-voucher')">
                            Voucher, SP & Acc
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100" x-data
                            x-on:click="$dispatch('open-modal','create-deposit')">
                            Penjualan Deposit
                        </a>
                    </x-slot>
                </x-dropdown>
                <x-dropdown align="left" width="w-64">
                    <x-slot name="trigger">
                        <x-button class="min-w-32" style="danger">
                            Pengeluaran (Biaya)
                        </x-button>
                    </x-slot>

                    <x-slot name="content">
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100" x-data
                            x-on:click="$dispatch('open-modal','create-mutation-to-hq')">
                            Pengembalian saldo kas & bank
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100" x-data
                            x-on:click="$dispatch('open-modal','create-expense')">
                            Biaya Operasional
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100" x-data
                            x-on:click="$dispatch('open-modal','create-bank-expense')">
                            Biaya Admin Bank
                        </a>
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
    <x-modal name="create-mutation-to-hq" :show="false" :title="'Pengembalian saldo kas & bank'">
        @livewire('transaction.create-mutation-to-hq')
    </x-modal>
    <x-modal name="create-expense" :show="false" :title="'Biaya Operasional'">
        @livewire('transaction.create-expense')
    </x-modal>
    <x-modal name="create-bank-expense" :show="false" :title="'Biaya Admin Bank'">
        @livewire('transaction.create-bank-expense')
    </x-modal>
    <x-modal name="create-voucher" :show="false" :title="'Penjualan Voucher'">
        @livewire('transaction.create-voucher')
    </x-modal>
    <x-modal name="create-deposit" :show="false" :title="'Penjualan Deposit'">
        @livewire('transaction.create-deposit')
    </x-modal>
    <div x-data="{ voucherOpen: false, pengeluaranOpen: false }"
        class="fixed left-0 bottom-0 w-screen z-[80] sm:hidden">
        <div x-show="voucherOpen" class="bg-white w-full flex" @click.away="voucherOpen = false">
            <button x-data x-on:click="$dispatch('open-modal', 'create-voucher')"
                class="w-full p-3 bg-sky-800 text-white text-xs hover:bg-sky-700">
                Voucher
            </button>
            <button x-data x-on:click="$dispatch('open-modal', 'create-deposit')"
                class="w-full p-3 bg-sky-800 text-white text-xs hover:bg-sky-700">
                Deposit
            </button>
        </div>
        <div x-show="pengeluaranOpen" class="bg-white w-full flex" @click.away="pengeluaranOpen = false">
            <button x-data x-on:click="$dispatch('open-modal', 'create-mutation-to-hq')"
                class="w-full p-3 bg-sky-800 text-white text-xs hover:bg-sky-700">
                Pengembalian Saldo
            </button>
            <button x-data x-on:click="$dispatch('open-modal', 'create-expense')"
                class="w-full p-3 bg-sky-800 text-white text-xs hover:bg-sky-700">
                B. Operasional
            </button>
            <button x-data x-on:click="$dispatch('open-modal', 'create-bank-expense')"
                class="w-full p-3 bg-sky-800 text-white text-xs hover:bg-sky-700">
                B. Admin Bank
            </button>
        </div>
        <div class="flex justify-between text-white">
            <button x-data x-on:click="$dispatch('open-modal', 'create-transfer')"
                class="flex flex-col items-center justify-evenly w-full bg-sky-950 hover:bg-sky-800 p-2 transition duration-300 ease-out">
                <h1 class="font-bold text-2xl"><i class="fa-solid fa-circle-arrow-up"></i></h1>
                <h4 class="text-xs">Transfer</h4>
            </button>
            <button x-data x-on:click="$dispatch('open-modal', 'create-withdrawal')"
                class="flex flex-col items-center justify-evenly w-full bg-sky-950 hover:bg-sky-800 p-2 transition duration-300 ease-out">
                <h1 class="font-bold text-2xl"><i class="fa-solid fa-circle-arrow-down"></i></h1>
                <h4 class="text-xs">Tarik Tunai</h4>
            </button>
            <button @click="voucherOpen = !voucherOpen"
                class="flex flex-col items-center justify-evenly w-full bg-sky-950 hover:bg-sky-800 p-2">
                <h1 class="font-bold text-2xl"><i class="fa-solid fa-ticket"></i></h1>
                <h4 class="text-xs">Voucher</h4>
            </button>
            <button @click="pengeluaranOpen = !pengeluaranOpen"
                class="flex flex-col items-center justify-evenly w-full bg-sky-950 hover:bg-sky-800 p-2">
                <h1 class="font-bold text-2xl"><i class="fa-solid fa-file-invoice-dollar"></i></h1>
                <h4 class="text-xs">Pengeluaran</h4>
            </button>
        </div>
    </div>
</x-app-layout>