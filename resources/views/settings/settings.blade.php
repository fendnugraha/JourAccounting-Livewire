<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <ul class="w-full sm:w-1/2 rounded-lg bg-white divide-y divide-gray-200">
                <li>
                    <x-link href="{{ route('settings.user.index') }}" class="block p-4 hover:bg-yellow-50">User</x-link>
                </li>
                <li>
                    <x-link href="{{ route('settings.account.index') }}" class="block p-4 hover:bg-yellow-50">Daftar
                        Akun (COA)</x-link>
                </li>
                <li>
                    <x-link href="{{ route('settings.warehouse.index') }}" class="block p-4 hover:bg-yellow-50">
                        Manajemen Cabang
                    </x-link>
                </li>
                <li>
                    <x-link href="{{ route('settings.product.index') }}" class="block p-4 hover:bg-yellow-50">
                        Product
                    </x-link>
                </li>
                <li>
                    <x-link href="{{ route('settings.contact.index') }}" class="block p-4 hover:bg-yellow-50">
                        Contact
                    </x-link>
                </li>
            </ul>

        </div>
    </div>
</x-app-layout>