<div class="card p-4">
    <h1 class="card-title mb-4">Detail {{ $finance_type == 'Receivable' ? 'Piutang' : 'Hutang' }}
        <span class="card-subtitle">Nama: {{ $contactName }}</span>
    </h1>
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
                        <button><i class="bi bi-trash"></i></button>
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