<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Finance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-primary-button x-data x-on:click="$dispatch('open-modal','create-payable')"
                class="bg-blue-600 text-white px-4 py-2 rounded mb-4">
                Tambah Hutang
            </x-primary-button>
            <x-primary-button x-data x-on:click="$dispatch('open-modal','create-contact')"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                Tambah kontak
            </x-primary-button>
            <x-modal name="create-payable" :show="false" :title="'Tambah Hutang'">
                @livewire('finance.create-payable')
            </x-modal>
            <x-modal name="create-contact" :show="false" :title="'Tambah kontak'">
                @livewire('settings.contact.create-contact')
            </x-modal>

            @livewire('finance.finance-table')

        </div>
    </div>

</x-app-layout>