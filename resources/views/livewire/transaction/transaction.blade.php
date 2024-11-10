<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-sub-navlinks :links="[
                        ['href' => route('transaction'), 'route' => 'transaction', 'text' => 'Summary'],
                        ['href' => route('transaction.sales'), 'route' => 'transaction/sales', 'text' => 'Sales'],
                        ['href' => route('transaction.purchases'), 'route' => 'transaction/purchases', 'text' => 'Purchases']
                    ]" />
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</div>