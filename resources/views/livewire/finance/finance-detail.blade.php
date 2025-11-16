<div class="card p-4">
    <div class="flex justify-between">
        <h1 class="card-title mb-4">Detail {{ $finance_type == 'Receivable' ? 'Piutang' : 'Hutang' }}
            <span class="card-subtitle">Nama: {{ $contactName }}, Periode: {{ $startDate }} sd {{ $endDate }}</span>
        </h1>
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
    <div class="overflow-x-auto">
        <table class="table w-full text-xs">
            <thead>
                <tr>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->finances as $finance)
                <tr>
                    <td>
                        <span class="block font-bold">{{ $finance->date_issued }} {{ $finance->account->acc_name
                            }}</span>
                        {{ $finance->description }}
                    </td>
                    <td
                        class="text-right text-sm font-bold {{ $finance->bill_amount > 0 ? 'text-red-500' : 'text-green-500' }}">
                        {{
                        Number::format($finance->bill_amount > 0 ?
                        $finance->bill_amount : $finance->payment_amount) }}</td>
                    <td class="text-center">
                        <button wire:confirm="Apakah anda yakin menghapus data ini?"
                            wire:click="delete({{ $finance->id }})"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $this->finances->links() }}
</div>