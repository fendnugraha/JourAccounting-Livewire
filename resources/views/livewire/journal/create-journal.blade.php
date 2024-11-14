<div>
    <form wire:submit="save">
        <div class="mb-4">
            <div class="mb-2">
                <label class="" for="date_issued">Tanggal</label>
            </div>
            <div>
                <input type="datetime-local" wire:model="date_issued"
                    class="w-full border rounded-lg p-2 @error('date_issued') border-red-500 @enderror">
                @error('date_issued') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mb-4">
            <div class="mb-2">
                <label class="" for="date_issued">Debet</label>
            </div>
            <div>
                <select class="w-full border rounded-lg p-2 @error('account') border-red-500 @enderror" name="account"
                    wire:model="account">
                    <option value="">--Pilih Account--</option>
                    @foreach ($credits as $account)
                    <option value="{{ $account->acc_code }}">{{ $account->acc_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-4">
            <div class="mb-2">
                <label class="" for="date_issued">Credit</label>
            </div>
            <div>
                <select class="w-full border rounded-lg p-2 @error('account') border-red-500 @enderror" name="account"
                    wire:model="account">
                    <option value="">--Pilih Account--</option>
                    @foreach ($credits as $account)
                    <option value="{{ $account->acc_code }}">{{ $account->acc_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-4">
            <div class="mb-2">
                <label class="" for="description">Keterangan</label>
            </div>
            <div>
                <input type="text" wire:model="description"
                    class="w-full border rounded-lg p-2 @error('description') border-red-500 @enderror">
                @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex justify-end gap-2 mt-6">
            <button class="bg-slate-200 hover:bg-slate-300 text-gray-700 py-4 px-14 rounded-2xl">
                Cancel
            </button>
            <button class="bg-gray-700 hover:bg-gray-600 text-white py-4 px-14 rounded-2xl">
                Simpan
            </button>
        </div>
    </form>
</div>