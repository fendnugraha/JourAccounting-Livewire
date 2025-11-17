<div>
    <div class="mb-4 card">
        <div class="px-4 sm:px-6 pt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <h1 class="card-title">
                Mutasi Saldo
                <span class="card-subtitle text-nowrap">Periode: {{ $endDate }}</span>
            </h1>
            <div class="sm:flex gap-2 w-full sm:col-span-2 h-fit">
                @can('admin')
                <div class="gap-2 sm:flex grid grid-cols-2 mb-2 sm:mb-0">
                    <button x-data x-on:click="$dispatch('open-modal','create-mutation-from-hq')"
                        class="bg-indigo-500 text-sm sm:text-xs min-w-36 hover:bg-indigo-600 text-white py-4 sm:py-2 px-2 sm:px-6 rounded-lg">
                        Mutasi Saldo
                    </button>
                </div>
                @endcan
                <div class="w-full flex justify-end gap-2 mb-2 sm:mb-0">
                    @can('admin')
                    <select class="form-select block w-fit p-2.5" wire:model.live="warehouse">
                        <option>Pilih Cabang</option>
                        @foreach ($warehouses as $wh)
                        <option value="{{ $wh->id }}" {{ $warehouse==$wh->id ? 'selected' : '' }}>{{
                            $wh->name }}</option>
                        @endforeach
                    </select>
                    @endcan
                    <button class="small-button" wire:click="refreshData">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    <button class="small-button" x-data
                        x-on:click="$dispatch('open-modal','filter-journal-mutation')"><i class=" bi bi-funnel"></i>
                    </button>
                    <x-modal name="filter-journal-mutation" :show="false" :title="'Filter'" :maxWidth="'sm'">
                        <div class="flex flex-col gap-2">
                            <x-input-label for="trx_type" :value="__('Tanggal')" />
                            <x-text-input wire:model.live="endDate" type="date" class="mt-1 block w-full" />
                        </div>
                    </x-modal>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table w-full text-xs">
                <thead>
                    <tr>
                        <th>Nama akun</th>
                        <th class="hidden sm:table-cell">Saldo Akhir</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                    <tr>
                        <td>
                            {{ $account->acc_name }}
                            <span class="text-xs text-blue-600 dark:text-blue-400 font-bold block sm:hidden">
                                {{ Number::format($account->balance ?? 0) }}
                            </span>
                        </td>
                        <td class="text-end font-bold hidden sm:table-cell">{{ Number::format($account->balance ?? 0) }}
                        </td>
                        <td class="text-end">{{ Number::format($debitMutasi[$account->id] ?? 0) }}</td>
                        <td class="text-end">{{ Number::format($creditMutasi[$account->id] ?? 0) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="font-bold ">Mutasi Saldo {{ $warehouse === 1 ? 'Dari' : 'Ke' }} HQ</td>
                        <td class="text-end font-bold hidden sm:table-cell"></td>
                        <td class="text-end font-bold"></td>
                        <td class="text-end font-bold {{ $remaining === 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $remaining === 0 ? 'Completed' : Number::format($remaining) }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="">
                        <th>
                            Total
                            <span class="font-bold text-blue-500 block sm:hidden">
                                {{ Number::format($accounts->sum('balance') ?? 0) }}
                            </span>
                        </th>
                        <th class="text-end font-bold hidden sm:table-cell">
                            {{ Number::format($accounts->sum('balance') ?? 0) }}
                        </th>
                        <th class="text-end">
                            {{ Number::format(array_sum($debitMutasi) ?? 0) }}
                        </th>
                        <th class="text-end">
                            {{ Number::format(array_sum($creditMutasi) ?? 0) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @livewire('dashboard.mutation-history', ['warehouse' => $warehouse, 'endDate' => $endDate], key('mutation-' .
    $warehouse))
    <x-modal name="create-mutation-from-hq" :show="false" :title="'Mutasi Saldo'">
        @livewire('dashboard.create-mutation-from-hq')
    </x-modal>
</div>