<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <ul class="w-1/2 rounded-lg bg-white divide-y divide-gray-200">
                <li><a href="#" class="block p-4 hover:bg-gray-50">User</a></li>
                <li><a href="{{ route('settings.account.index') }}" class="block p-4 hover:bg-gray-50"
                        wire:navigate>Chart of
                        Accounts</a></li>
                <li><a href="#" class="block p-4 hover:bg-gray-50">Warehouse</a></li>
                <li><a href="#" class="block p-4 hover:bg-gray-50">Product</a></li>
                <li><a href="#" class="block p-4 hover:bg-gray-50">Contact</a></li>
            </ul>

        </div>
    </div>
</x-app-layout>