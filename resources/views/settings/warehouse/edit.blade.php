<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @livewire('settings.warehouse.edit-warehouse', ['warehouse' => $warehouse])
                @livewire('settings.warehouse.warehouse-detail', ['warehouse' => $warehouse])
            </div>
        </div>
    </div>

</x-app-layout>