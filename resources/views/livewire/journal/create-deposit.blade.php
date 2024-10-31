<div>
    @if(session('success'))
    <x-notification class="bg-green-500 text-white mb-3">
        <strong><i class="fas fa-check-circle"></i> Success!!</strong>
    </x-notification>
    @elseif (session('error'))
    <x-notification class="bg-red-500 text-white mb-3">
        <strong>Error!!</strong> {{
        session('error') }}
    </x-notification>
    @endif
    <form wire:submit="save">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 mb-2 items-center">
            <label for="date_issued" class="block ">Tanggal</label>
            <div class="col-span-2">
                <input type="datetime-local" wire:model="date_issued"
                    class="w-full border rounded-lg p-2 @error('date_issued') border-red-500 @enderror">
                @error('date_issued') <small class="text-red-500">{{ $message }}</small> @enderror
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 mb-2 items-center">
            <label for="debt_code" class="block ">Akun Debit</label>
            <div class="col-span-2">
                <select wire:model="debt_code"
                    class="w-full border rounded-lg p-2 @error('debt_code') border-red-500 @enderror">
                    <option value="">--Pilih Akun Debit--</option>
                    @foreach ($credits as $credit)
                    <option value="{{ $credit->acc_code }}">{{ $credit->acc_name }}</option>
                    @endforeach
                </select>
                @error('debt_code') <small class="text-red-500">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 mb-2 items-center" x-data="{ amount: '' }">
            <label for="amount" class="block">Jumlah transfer</label>
            <div class="">
                <input type="number" x-model="amount" wire:model="amount"
                    class="w-full border rounded-lg p-2 @error('amount') border-red-500 @enderror" placeholder="Rp">
                @error('amount') <small class="text-red-500">{{ $message }}</small> @enderror
            </div>
            <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(amount)"
                class="text-sky-500 italic font-bold text-lg text-right"></span>
        </div>
        <div class="mb-2 mt-2">
            <label for="description" class="block">Description</label>
            <textarea wire:model="description"
                class="w-full border rounded-lg p-2 @error('description') border-red-500 @enderror"
                placeholder="Keterangan (Optional)"></textarea>
            @error('description') <small class="text-red-500">{{ $message }}</small> @enderror
        </div>

        <div class="grid grid-cols-2 gap-1 mt-4 items-center">
            <button type="submit"
                class="w-full bg-blue-500 text-white p-2 rounded-lg disabled:bg-slate-400 disabled:cursor-none"
                wire:loading.attr="disabled">Simpan <span wire:loading><i
                        class="fa-solid fa-spinner animate-spin"></i></span></button>
        </div>
    </form>
</div>