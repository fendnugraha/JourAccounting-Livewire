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
                        <div class="sm:p-4 px-4 py-2 bg-gray-600 text-white shadow-sm rounded-xl">
                            <h5 class="">Total Assets</h5>
                            <h2 class="text-end font-bold text-2xl sm:text-3xl"><i class="fa-solid fa-bank"></i> {{
                                number_format(intval($assets->flatten()->sum('balance'))) }}</h2>
                        </div>
                        <div class="sm:p-4 px-4 py-2 bg-gray-600 text-white shadow-sm rounded-xl">
                            <h5 class="">Total Liabilities</h5>
                            <h2 class="text-end font-bold text-2xl sm:text-3xl"><i
                                    class="fa-solid fa-file-invoice-dollar"></i> {{
                                number_format(intval($liabilities->flatten()->sum('balance') +
                                $equity->flatten()->sum('balance'))) }}</h2>
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-2 mb-4">
                        <div class="">
                            @foreach ($assets as $accountGroup)
                            <table class="table-auto w-full text-sm mb-2 bg-white" {{ $accountGroup->sum('balance') !==
                                0 ? '' : 'hidden' }}>
                                <thead>
                                    <tr class="border-b" {{ $accountGroup->sum('balance') !==
                                        0 ? '' : 'hidden' }}>
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
                            @foreach ($liabilities as $accountGroup)
                            <table class="table-auto w-full text-sm mb-2 bg-white" {{ $accountGroup->sum('balance') !==
                                0 ? '' : 'hidden' }}>
                                <thead>
                                    <tr class="border-b" {{ $accountGroup->sum('balance') !== 0 ? '' : 'hidden' }}>
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
                            @foreach ($equity as $accountGroup)
                            <table class="table-auto w-full text-sm mb-2 bg-white" {{ $accountGroup->sum('balance') !==
                                0 ? '' : 'hidden' }}>
                                <thead>
                                    <tr class="border-b" {{ $accountGroup->sum('balance') !== 0 ? '' : 'hidden' }}>
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