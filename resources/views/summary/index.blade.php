<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Summary') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('summary.warehouse-balance')
            @livewire('summary.revenue-report')
            @livewire('summary.generaledger')
            @livewire('summary.log-activity')
        </div>
    </div>
</x-app-layout>