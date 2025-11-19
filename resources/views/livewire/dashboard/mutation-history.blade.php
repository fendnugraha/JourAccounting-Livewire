<div class="card p-4">
    <x-loading />
    <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <h1 class="card-title">History Mutasi Saldo</h1>
        <div class="flex justify-end gap-2">
            <!-- $search dan $perPage sudah benar menggunakan wire:model.live -->
            <input type="search" class="form-select block w-full p-2.5" wire:model.debounce.500ms.live="search"
                placeholder="Search..." />

            <select class="form-select block !w-fit p-2.5" wire:model.live="perPage">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="table w-full text-xs">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- PASTIKAN MENGGUNAKAN $this->journals -->
                @forelse ($this->journals as $journal)
                <tr>
                    <td>
                        <!-- Menggunakan Carbon untuk memformat tanggal agar lebih rapi -->
                        <span class="block font-bold">{{ \Carbon\Carbon::parse($journal->date_issued)->format('d M Y')
                            }} - {{ $journal->invoice }}</span>

                        <!-- Transaksi dari ke akun -->
                        {{ $journal->cred->acc_name }}
                        <i class="bi bi-arrow-right font-bold"></i>
                        {{ $journal->debt->acc_name }}
                    </td>
                    <td
                        class="text-end text-lg font-bold {{ $journal->debt->warehouse_id == $warehouse ? 'text-green-500' : 'text-red-500' }}">
                        <!-- Anda bisa menggunakan helper Number::format(..), atau number_format(..) standar PHP -->
                        {{ Number::format($journal->amount) }}
                    </td>
                    <td class="text-center">
                        <button wire:confirm="Apakah anda yakin menghapus data ini?" @class(['text-red-500 font-bold
                            disabled:text-slate-300', !$accounts->contains('id', $journal->cred_code) ? 'hidden' : ''])
                            wire:click="destroy({{ $journal->id }})"><i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- AKTIFKAN KEMBALI PAGINATION DENGAN SINTAKS $this->journals -->
    <div class="mt-4">
        {{ $this->journals->links() }}
    </div>
</div>