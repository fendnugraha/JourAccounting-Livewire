<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="text-gray-900">
                    <div class="mb-4">
                        <label for="periode" class="text-sm">Periode</label>
                        <input type="datetime-local" wire:model.live="startDate"
                            class="w-52 text-sm border rounded-lg p-2">
                        <label for="periode" class="text-sm">Sampai</label>
                        <input type="datetime-local" wire:model.live="endDate"
                            class="w-52 text-sm border rounded-lg p-2">
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="p-4 bg-gray-600 text-white shadow-sm rounded-xl">
                            <h5 class="">Kenaikan Penurunan Kas</h5>
                            <h2 class="text-end font-bold text-2xl sm:text-3xl">{{ number_format(intval($growth)) }}
                            </h2>
                        </div>
                        <div class="p-4 bg-gray-600 text-white shadow-sm rounded-xl">
                            <h5 class="">Growth Rate (%)</h5>
                            <h2 class="text-end font-bold text-2xl sm:text-3xl">{!! $percentageChange > 0 ? '<i
                                    class="fa-solid fa-circle-up text-light"></i>' : '<i
                                    class="fa-solid fa-circle-down text-danger"></i>' !!} {{
                                number_format($percentageChange, 2). ' %' }}</h2>
                        </div>
                    </div>
                    <div class="">
                        @foreach ($pendapatan as $accountGroup)
                        <table class="table-auto w-full text-sm mb-2 bg-white" {{$accountGroup->sum('balance') !== 0 ?
                            '' :
                            'hidden'}}>
                            <thead>
                                <tr class="border-b">
                                    <th class="p-2 sm:p-3 text-start">{{ $accountGroup->first()->account->name }}</th>
                                    <th class="text-end p-2 sm:p-3">{{
                                        number_format(intval($accountGroup->sum('balance'))) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accountGroup as $account)
                                <tr {{$account->balance !== 0 ? '' : 'hidden'}}>
                                    <td class="p-2 sm:p-3 text-start">{{ $account->acc_name }}</td>
                                    <td class="text-end p-2 sm:p-3">{{ number_format(intval($account->balance)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endforeach
                    </div>
                    <div class="">
                        @foreach ($persediaan as $accountGroup)
                        <table class="table-auto w-full text-sm mb-2 bg-white" {{$accountGroup->sum('balance') !== 0 ?
                            '' :
                            'hidden'}}>
                            <thead>
                                <tr class="border-b">
                                    <th class="p-2 sm:p-3 text-start">{{ $accountGroup->first()->account->name }}</th>
                                    <th class="text-end p-2 sm:p-3">{{
                                        number_format(intval($accountGroup->sum('balance'))) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accountGroup as $account)
                                <tr {{$account->balance !== 0 ? '' : 'hidden'}}>
                                    <td class="p-2 sm:p-3 text-start">{{ $account->acc_name }}</td>
                                    <td class="text-end p-2 sm:p-3">{{ number_format(intval($account->balance)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endforeach
                    </div>
                    <div class="">
                        @foreach ($investasi as $accountGroup)
                        <table class="table-auto w-full text-sm mb-2 bg-white" {{$accountGroup->sum('balance') !== 0 ?
                            '' :
                            'hidden'}}>
                            <thead>
                                <tr class="border-b">
                                    <th class="p-2 sm:p-3 text-start">{{ $accountGroup->first()->account->name }}</th>
                                    <th class="text-end p-2 sm:p-3">{{
                                        number_format(intval($accountGroup->sum('balance'))) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accountGroup as $account)
                                <tr {{$account->balance !== 0 ? '' : 'hidden'}}>
                                    <td class="p-2 sm:p-3 text-start">{{ $account->acc_name }}</td>
                                    <td class="text-end p-2 sm:p-3">{{ number_format(intval($account->balance)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endforeach
                    </div>
                    <div class="">
                        @foreach ($assets as $accountGroup)
                        <table class="table-auto w-full text-sm mb-2 bg-white" {{$accountGroup->sum('balance') !== 0 ?
                            '' :
                            'hidden'}}>
                            <thead>
                                <tr class="border-b">
                                    <th class="p-2 sm:p-3 text-start">{{ $accountGroup->first()->account->name }}</th>
                                    <th class="text-end p-2 sm:p-3">{{
                                        number_format(intval($accountGroup->sum('balance'))) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accountGroup as $account)
                                <tr {{$account->balance !== 0 ? '' : 'hidden'}}>
                                    <td class="p-2 sm:p-3 text-start">{{ $account->acc_name }}</td>
                                    <td class="text-end p-2 sm:p-3">{{ number_format(intval($account->balance)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endforeach
                    </div>
                    <div class="">
                        @foreach ($piutang as $accountGroup)
                        <table class="table-auto w-full text-sm mb-2 bg-white" {{$accountGroup->sum('balance') !== 0 ?
                            '' :
                            'hidden'}}>
                            <thead>
                                <tr class="border-b">
                                    <th class="p-2 sm:p-3 text-start">{{ $accountGroup->first()->account->name }}</th>
                                    <th class="text-end p-2 sm:p-3">{{
                                        number_format(intval($accountGroup->sum('balance'))) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accountGroup as $account)
                                <tr {{$account->balance !== 0 ? '' : 'hidden'}}>
                                    <td class="p-2 sm:p-3 text-start">{{ $account->acc_name }}</td>
                                    <td class="text-end p-2 sm:p-3">{{ number_format(intval($account->balance)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endforeach
                    </div>
                    <div class="">
                        @foreach ($hutang as $accountGroup)
                        <table class="table-auto w-full text-sm mb-2 bg-white" {{$accountGroup->sum('balance') !== 0 ?
                            '' :
                            'hidden'}}>
                            <thead>
                                <tr class="border-b">
                                    <th class="p-2 sm:p-3 text-start">{{ $accountGroup->first()->account->name }}</th>
                                    <th class="text-end p-2 sm:p-3">{{
                                        number_format(intval($accountGroup->sum('balance'))) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accountGroup as $account)
                                <tr {{$account->balance !== 0 ? '' : 'hidden'}}>
                                    <td class="p-2 sm:p-3 text-start">{{ $account->acc_name }}</td>
                                    <td class="text-end p-2 sm:p-3">{{ number_format(intval($account->balance)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endforeach
                    </div>
                    <div class="">
                        @foreach ($modal as $accountGroup)
                        <table class="table-auto w-full text-sm mb-2 bg-white" {{$accountGroup->sum('balance') !== 0 ?
                            '' :
                            'hidden'}}>
                            <thead>
                                <tr class="border-b">
                                    <th class="p-2 sm:p-3 text-start">{{ $accountGroup->first()->account->name }}</th>
                                    <th class="text-end p-2 sm:p-3">{{
                                        number_format(intval($accountGroup->sum('balance'))) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accountGroup as $account)
                                <tr {{$account->balance !== 0 ? '' : 'hidden'}}>
                                    <td class="p-2 sm:p-3 text-start">{{ $account->acc_name }}</td>
                                    <td class="text-end p-2 sm:p-3">{{ number_format(intval($account->balance)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endforeach
                    </div>
                    <div class="">
                        @foreach ($biaya as $accountGroup)
                        <table class="table-auto w-full text-sm mb-2 bg-white" {{$accountGroup->sum('balance') !== 0 ?
                            '' :
                            'hidden'}}>
                            <thead>
                                <tr class="border-b">
                                    <th class="p-2 sm:p-3 text-start">{{ $accountGroup->first()->account->name }}</th>
                                    <th class="text-end p-2 sm:p-3">{{
                                        number_format(intval($accountGroup->sum('balance'))) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accountGroup as $account)
                                <tr {{$account->balance !== 0 ? '' : 'hidden'}}>
                                    <td class="p-2 sm:p-3 text-start">{{ $account->acc_name }}</td>
                                    <td class="text-end p-2 sm:p-3">{{ number_format(intval($account->balance)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endforeach
                    </div>
                    <div class="">
                        <table class="table-auto w-full text-sm bg-white">
                            <thead>
                                <tr class="border-b">
                                    <th class="p-2 sm:p-3 text-start">Saldo Awal Kas</th>
                                    <th class="text-end p-2 sm:p-3">{{ number_format(intval($startBalance)) }}</th>
                                </tr>
                                <tr class="border-b">
                                    <th class="p-2 sm:p-3 text-start">Saldo Akhir Kas</th>
                                    <th class="p-2 sm:p-3 text-end">{{ number_format(intval($endBalance)) }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>