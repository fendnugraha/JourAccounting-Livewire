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
                <label class="" for="contact">Contact</label>
                <div class="col-span-2">
                    <select class="w-full border rounded-lg p-2 text-sm @error('contact') border-red-500 @enderror"
                        name="contact" wire:model.live="contact">
                        <option value="">--Pilih Contact--</option>
                        @foreach ($contacts as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('contact')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="mb-4">
            <div class="grid grid-cols-3 gap-4 items-center">
                <label class="" for="invoice">No Invoice</label>
                <div class="col-span-2">
                    <select class="w-full border rounded-lg p-2 text-sm @error('invoice') border-red-500 @enderror"
                        name="invoice" wire:model="invoice">
                        <option value="">--Pilih no invoice--</option>
                        @foreach ($receivables->where('contact_id', $contact) as $i)
                        <option value="{{ $i->invoice }}">{{ $i->invoice }} -> Rp. {{ number_format($i->total) }}
                        </option>
                        @endforeach
                    </select>
                    @error('invoice')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
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
            <button type="submit"
                class="bg-blue-700 hover:bg-blue-600 text-white py-4 px-14 rounded-2xl disabled:bg-slate-300 disabled:cursor-none"
                wire:loading.attr="disabled">
                Simpan <span wire:loading><i class="fa-solid fa-spinner animate-spin"></i></span>
            </button>
        </div>
    </form>
    @if(session('success'))
    <span>{{ session('success') }}</span>
    @elseif (session('error'))
    <span>{{ session('error') }}</span>
    @endif
</div>