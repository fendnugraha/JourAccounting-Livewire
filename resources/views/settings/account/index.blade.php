<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-primary-button x-data x-on:click="$dispatch('open-modal','create-user')"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                Open Modal
            </x-primary-button>

            <x-modal name="create-user" :show="false" :title="'Create Chart of Account'">

                <p class="mt-2">
                    Isi konten modal di sini.
                </p>

                <div class="mt-4 text-right">
                    <button x-on:click="$dispatch('close-modal','create-user')"
                        class="px-4 py-2 bg-gray-700 text-white rounded">
                        Close
                    </button>
                </div>
            </x-modal>

        </div>
    </div>
</x-app-layout>