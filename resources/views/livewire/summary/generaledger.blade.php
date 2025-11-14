<div class="card mt-12 p-4">
    <h1 class="card-title mb-4">History Mutasi Account</h1>
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-1 sm:gap-3 mb-2 sm:col-span-2">
        <div
            class="border border-slate-300 dark:bg-slate-600 p-2 sm:px-4 sm:py-2 rounded-xl text-slate-700 dark:text-slate-100 dark:border-slate-600">
            <h5 class="sm:text-xs">Saldo Awal</h5>
            <span class="sm:text-xl font-bold">{{ Number::format($initBalance) }}</span>
        </div>
        <div
            class="border border-slate-300 dark:bg-slate-600 p-2 sm:px-4 sm:py-2 rounded-xl text-slate-700 dark:text-slate-100 dark:border-slate-600">
            <h5 class="sm:text-xs">Debet</h5>
            <span class="sm:text-xl font-bold">{{ Number::format($debt_total) }}</span>
        </div>
        <div
            class="border border-slate-300 dark:bg-slate-600 p-2 sm:px-4 sm:py-2 rounded-xl text-slate-700 dark:text-slate-100 dark:border-slate-600">
            <h5 class="sm:text-xs">Credit</h5>
            <span class="sm:text-xl font-bold">{{ Number::format($cred_total) }}</span>
        </div>
        <div
            class="border border-slate-300 dark:bg-slate-600 p-2 sm:px-4 sm:py-2 rounded-xl text-slate-700 dark:text-slate-100 dark:border-slate-600">
            <h5 class="sm:text-xs">Saldo Akhir</h5>
            <span class="sm:text-xl font-bold">{{ Number::format($endBalance) }}</span>
        </div>
    </div>
    <div class="w-full flex justify-end gap-2 mb-4">
        <select class="form-select block w-fit p-2.5" wire:model.live="account">
            @foreach ($accounts as $ac)
            <option value="{{ $ac->id }}" {{ $account==$ac->id ? 'selected' : '' }}>{{
                $ac->acc_name }}</option>
            @endforeach
        </select>
        <button class="small-button" wire:click="$refresh">
            <i class="bi bi-arrow-clockwise"></i>
        </button>
        <button class="small-button">
            <i class="bi bi-funnel"></i>
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="table w-full text-xs">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Jumlah</th>
                    <th>Fee(admin)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->journals as $j)
                <tr>
                    <td class=""><span class="font-bold block">{{ $j->date_issued }} {{ $j->invoice }} </span>
                        {{ $j->debt_code == $account ? $j->cred->acc_name : $j->debt->acc_name }} - {{ $j->description
                        }}</td>
                    <td
                        class="text-right text-lg font-bold {{ $j->debt_code == $account ? 'text-red-500' : 'text-green-500' }}">
                        {{ Number::format($j->amount) }}</td>
                    <td
                        class="text-right text-lg font-bold {{ $j->debt_code == $account ? 'text-red-500' : 'text-green-500' }}">
                        {{ Number::format($j->fee_amount) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>