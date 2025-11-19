<div class="card p-4">
    <x-loading />
    <div class="flex justify-between">
        <h1 class="card-title mb-4">Saldo Cabang
            <span class="card-subtitle">Periode: {{ $endDate }}</span>
        </h1>
        <x-text-input type="date" class="form-control block !w-fit p-2.5 h-fit" wire:model.live="endDate" />
    </div>
    <div class="overflow-x-auto">
        <table class="table w-full text-xs">
            <thead>
                <tr>
                    <th class="text-center">Cabang</th>
                    <th class="text-center">Kas Tunai</th>
                    <th class="text-center">Bank</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($warehouses as $warehouse)
                <tr>
                    <td class="">{{ strtoupper($warehouse['name']) }}</td>
                    <td class="text-right">{{ Number::format($warehouse['cash']) }}</td>
                    <td class="text-right">{{ Number::format($warehouse['bank']) }}</td>
                    <td class="text-right">{{ Number::format($warehouse['bank'] + $warehouse['cash']) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-right">{{ Number::format($totalCash) }}</th>
                    <th class="text-right">{{ Number::format($totalBank) }}</th>
                    <th class="text-right">{{ Number::format($totalBank + $totalCash) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>