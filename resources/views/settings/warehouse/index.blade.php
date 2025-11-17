<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-2 mb-4">
                <a href="{{ route('settings') }}" class="self-center" wire:navigate><i
                        class="bi bi-arrow-left w-5 h-5"></i></a>
                <x-primary-button x-data x-on:click="$dispatch('open-modal','create-warehouse')"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    Tambah Cabang
                </x-primary-button>
                <x-primary-button x-data x-on:click="$dispatch('open-modal','create-account')"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    Tambah Account
                </x-primary-button>
            </div>

            <x-modal name="create-warehouse" :show="false" :title="'Tambah Cabang'">
                @livewire('settings.warehouse.create-warehouse')
            </x-modal>
            <x-modal name="create-account" :show="false" :title="'Tambah Account'">
                @livewire('settings.account.create-account')
            </x-modal>

            @livewire('settings.warehouse.warehouse-table')
        </div>
    </div>

</x-app-layout>