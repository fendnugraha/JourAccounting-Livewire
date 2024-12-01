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
                    {{-- <div class="flex gap-2 items-center mb-2">
                        <label for="endDate" class="text-sm">Pilih Periode</label>
                        <select class="rounded-lg border text-sm p-2 min-w-44" wire:model.live="month">
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                        <select class="rounded-lg border text-sm p-2 min-w-24" wire:model.live="year">
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                        </select>
                    </div> --}}
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
                                $equityCount)) }}</h2>
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