<div>
    <form wire:submit="save">
        <div class="mb-4">
            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="" for="date_issued">Tanggal</label>
                <div class="col-span-2">
                    <input type="datetime-local" wire:model="date_issued"
                        class="w-full border rounded-lg p-2 text-sm @error('date_issued') border-red-500 @enderror">
                    @error('date_issued') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="mb-4">
            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="" for="debt_code">Sumber Dana</label>
                <div class="col-span-2">
                    <select class="w-full border rounded-lg p-2 text-sm @error('debt_code') border-red-500 @enderror"
                        name="debt_code" wire:model="debt_code">
                        <option value="">--Pilih sumber dana--</option>
                        @foreach ($credits as $account)
                        <option value="{{ $account->acc_code }}">{{ $account->acc_name }}</option>
                        @endforeach
                    </select>
                    @error('debt_code')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="mb-4">
            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="" for="cred_code">Tujuan</label>
                <div class="col-span-2">
                    <select class="w-full border rounded-lg p-2 text-sm @error('cred_code') border-red-500 @enderror"
                        name="cred_code" wire:model="cred_code">
                        <option value="">--Pilih tujuan mutasi--</option>
                        @foreach ($credits as $i)
                        <option value="{{ $i->acc_code }}">{{ $i->acc_name }}</option>
                        @endforeach
                    </select>
                    @error('cred_code')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="mb-4">
            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="" for="amount">Jumlah</label>
                <div>
                    <input type="number" wire:model="amount" placeholder="Rp. "
                        class="w-full border rounded-lg p-2 text-sm @error('amount') border-red-500 @enderror">
                    @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="mb-4">
            <div class="mb-2">
                <label class="" for="description">Keterangan</label>
            </div>
            <div>
                <textarea cols="30" rows="2" wire:model="description" placeholder="Keterangan (Optional)"
                    class="w-full border rounded-lg p-2 text-sm @error('description') border-red-500 @enderror"></textarea>
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex justify-end gap-2 mt-6">
            <button class="bg-slate-200 hover:bg-slate-300 text-gray-700 py-4 px-14 rounded-2xl">
                Cancel
            </button>
            <button type="submit"
                class="bg-gray-700 hover:bg-gray-600 text-white py-4 px-14 rounded-2xl disabled:bg-slate-300 disabled:cursor-none"
                wire:loading.attr="disabled">
                Simpan <span wire:loading><i class="fa-solid fa-spinner animate-spin"></i></span>
            </button>
        </div>
    </form>
</div>