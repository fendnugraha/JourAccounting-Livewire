<div class="mt-12 flex gap-4">
    <div class="card p-4 w-3/4">
        <h1 class="card-title mb-4">Pengeluaran Operasional</h1>
        <div class="overflow-x-auto">
            <table class="table w-full text-xs">
                <thead>
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                    <tr>
                        <td class="text-center">{{ $expense->date }}</td>
                        <td>{{ $expense->description }}</td>
                        <td class="text-right">{{ Number::format($expense->amount) }}</td>
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
        <h1 class="text-2xl font-bold">{{ Number::format($expenses->sum('amount')) }}</h1>
    </div>
</div>