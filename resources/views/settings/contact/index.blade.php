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
                <x-primary-button x-data x-on:click="$dispatch('open-modal','create-contact')"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    Tambah kontak
                </x-primary-button>
            </div>

            <x-modal name="create-contact" :show="false" :title="'Tambah kontak'">
                @livewire('settings.contact.create-contact')
            </x-modal>

            @livewire('settings.contact.contact-table')
        </div>
    </div>

</x-app-layout>