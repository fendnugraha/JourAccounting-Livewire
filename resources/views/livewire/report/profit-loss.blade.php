<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <div class="text-gray-900">
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="px-4 py-2 sm:p-4 bg-gray-600 text-white shadow-sm rounded-xl">
                            <h5 class="">Total Revenue</h5>
                            <h2 class="text-end font-bold text-3xl"><i class="fa-solid fa-cash-register"></i> {{
                                number_format(intval($revenue->flatten()->sum('balance'))) }}</h2>
                        </div>
                        <div class="px-4 py-2 sm:p-4 bg-gray-600 text-white shadow-sm rounded-xl">
                            <h5 class="">Net Profit</h5>
                            <h2 class="text-end font-bold text-3xl"><i class="fa-solid fa-file-invoice"></i> {{
                                number_format(intval($revenue->flatten()->sum('balance') -
                                $cost->flatten()->sum('balance')) - $expense->flatten()->sum('balance')) }}</h2>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
                        <div class="">
                            @foreach ($revenue as $accountGroup)
                            <table class="table-auto w-full text-sm mb-2 bg-white" {{ $accountGroup->sum('balance') !==
                                0 ? '' : 'hidden' }}>
                                <thead>
                                    <tr class="border-b">
                                        <th class="p-2 sm:p-3 text-start">{{ $accountGroup->first()->account->name }}
                                        </th>
                                        <th class="text-end p-2 sm:p-3">{{
                                            number_format(intval($accountGroup->sum('balance')))
                                            }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accountGroup as $account)
                                    <tr class="border-b" {{ $account->balance !== 0 ? '' : 'hidden' }}>
                                        <td class="p-2 sm:p-3 text-start">{{ $account->acc_name }}</td>
                                        <td class="p-2 sm:p-3 text-end">{{ number_format(intval($account->balance)) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endforeach
                            @foreach ($cost as $accountGroup)
                            <table class="table-auto w-full text-sm mb-2 bg-white" {{ $accountGroup->sum('balance') !==
                                0 ? '' : 'hidden' }}>
                                <thead>
                                    <tr class="border-b">
                                        <th class="p-2 sm:p-3 text-start">{{ $accountGroup->first()->account->name }}
                                        </th>
                                        <th class="text-end p-2 sm:p-3">{{
                                            number_format(intval($accountGroup->sum('balance')))
                                            }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accountGroup as $account)
                                    <tr class="border-b" {{ $account->balance !== 0 ? '' : 'hidden' }}>
                                        <td class="p-2 sm:p-3 text-start">{{ $account->acc_name }}</td>
                                        <td class="p-2 sm:p-3 text-end">{{ number_format(intval($account->balance)) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endforeach
                        </div>
                        <div class="">
                            @foreach ($expense as $accountGroup)
                            <table class="table-auto w-full text-sm mb-2 bg-white" {{ $accountGroup->sum('balance') !==
                                0 ? '' : 'hidden' }}>
                                <thead>
                                    <tr class="border-b">
                                        <th class="p-2 sm:p-3 text-start">{{ $accountGroup->first()->account->name }}
                                        </th>
                                        <th class="text-end p-2 sm:p-3">{{
                                            number_format(intval($accountGroup->sum('balance')))
                                            }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accountGroup as $account)
                                    <tr class="border-b" {{ $account->balance !== 0 ? '' : 'hidden' }}>
                                        <td class="p-2 sm:p-3 text-start">{{ $account->acc_name }}</td>
                                        <td class="p-2 sm:p-3 text-end">{{ number_format(intval($account->balance)) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>