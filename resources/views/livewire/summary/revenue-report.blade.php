<div class="card mt-12 p-4">
    <div class="flex justify-between">
        <h1 class="card-title mb-4">Pendapatan
            <span class="card-subtitle">Periode: {{ $startDate }} sd {{ $endDate }}</span>
        </h1>
        <div class="flex gap-1">
            <button class="small-button"><i class="bi bi-arrow-clockwise" wire:click="$refresh"></i></button>
            <button class="small-button" x-data x-on:click="$dispatch('open-modal','filter-revenue')"><i
                    class=" bi bi-funnel"></i>
            </button>
            <x-modal name="filter-revenue" :show="false" :title="'Filter'" :maxWidth="'sm'">
                <div class="flex flex-col gap-2">
                    <x-input-label for="trx_type" :value="__('Dari')" />
                    <x-text-input wire:model.live="startDate" type="date" class="mt-1 block w-full" />
                    <x-input-label for="trx_type" :value="__('Sampai')" />
                    <x-text-input wire:model.live="endDate" type="date" class="mt-1 block w-full" />
                </div>
            </x-modal>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table w-full text-xs">
            <thead>
                <tr>
                    <th class="text-center">Cabang</th>
                    <th class="text-center">Transfer</th>
                    <th class="text-center">Tarik Tunai</th>
                    <th class="text-center">Voucher</th>
                    <th class="text-center">Acc.</th>
                    <th class="text-center">Deposit</th>
                    <th class="text-center">Biaya</th>
                    <th class="text-center">Laba Bersih</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($revenues as $r)
                <tr>
                    <td class="">{{ $r['warehouse'] }}</td>
                    <td class="text-right">{{ Number::format($r['transfer']) }}</td>
                    <td class="text-right">{{ Number::format($r['withdrawal']) }}</td>
                    <td class="text-right">{{ Number::format($r['voucher']) }}</td>
                    <td class="text-right">{{ Number::format($r['accessory']) }}</td>
                    <td class="text-right">{{ Number::format($r['deposit']) }}</td>
                    <td class="text-right">{{ Number::format($r['expense']) }}</td>
                    <td class="text-right">{{ Number::format($r['profit']) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-right">{{ Number::format($revenues->sum('transfer')) }}</th>
                    <th class="text-right">{{ Number::format($revenues->sum('withdrawal')) }}</th>
                    <th class="text-right">{{ Number::format($revenues->sum('voucher')) }}</th>
                    <th class="text-right">{{ Number::format($revenues->sum('accessory')) }}</th>
                    <th class="text-right">{{ Number::format($revenues->sum('deposit')) }}</th>
                    <th class="text-right">{{ Number::format($revenues->sum('expense')) }}</th>
                    <th class="text-right">
                        {{ Number::format($revenues->sum('profit')) }}
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>