<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-4 mb-4">
                <a href="{{ route('settings') }}" class="self-center" wire:navigate><i
                        class="bi bi-arrow-left w-5 h-5"></i></a>
                <x-primary-button x-data x-on:click="$dispatch('open-modal','create-product')"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    Tambah produk
                </x-primary-button>
            </div>

            <x-modal name="create-product" :show="false" :title="'Tambah produk'">
                @livewire('settings.product.create-product')
            </x-modal>

            @livewire('settings.product.product-table')
        </div>
    </div>

</x-app-layout>