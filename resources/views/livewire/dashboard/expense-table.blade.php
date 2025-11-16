<div class="mt-12 flex gap-4">
    <div class="card p-4 w-3/4">
        <div class="grid grid-cols-1 sm:grid-cols-2">
            <h1 class="card-title mb-4">Pengeluaran Operasional
                <span class="card-subtitle">Periode: {{ $startDate }} - {{ $endDate }}</span>
            </h1>
            <div class="w-full h-fit flex justify-end gap-2 mb-2 sm:mb-0">
                <select class="form-select block w-full p-2.5" wire:model.live="warehouse">
                    <option>Pilih Cabang</option>
                    @foreach ($warehouses as $wh)
                    <option value="{{ $wh->id }}" {{ $warehouse==$wh->id ? 'selected' : '' }}>{{
                        $wh->name }}</option>
                    @endforeach
                </select>
                <button class="small-button" wire:click="refreshData">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <button class="small-button" x-data x-on:click="$dispatch('open-modal','filter-voucher')"><i
                        class=" bi bi-funnel"></i>
                </button>
                <x-modal name="filter-voucher" :show="false" :title="'Filter'" :maxWidth="'sm'">
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
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                    <tr>
                        <td><span class="block font-bold">{{ $expense->date_issued }} {{ $expense->cred->acc_name
                                }}</span>
                            {{ $expense->description }}
                        </td>
                        <td class="text-right text-sm font-bold">{{ Number::format(-$expense->fee_amount) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $expenses->links(data: ['scrollTo' => false]) }}

    </div>
    <div class="card p-4 w-1/4 flex flex-col justify-center items-center !bg-red-500 text-white">
        <h1 class="">Total</h1>
        <h1 class="text-2xl font-bold">{{ Number::format(-$expenses->sum('fee_amount')) }}</h1>
    </div>
</div>