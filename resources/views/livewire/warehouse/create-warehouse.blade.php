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
    <div wire:loading class="bg-violet-500 text-white p-2 rounded-lg mb-3 w-full">
        Menyimpan data ...
    </div>

    <form wire:submit="save">
        <div class="">
            <div class="mb-2">
                <label for="code" class="block">Code</label>
                <input type="text" wire:model="code"
                    class="w-full border rounded-lg p-2 @error('code') border-red-500 @enderror"
                    placeholder="Contoh: W01">
                @error('code') <small class="text-red-500">{{ $message }}</small> @enderror
            </div>
            <div class="mb-2">
                <label for="name" class="block">Name</label>
                <input type="text" wire:model="name"
                    class="w-full border rounded-lg p-2 @error('name') border-red-500 @enderror">
                @error('name') <small class="text-red-500">{{ $message }}</small> @enderror

            </div>
            <div class="mb-2">
                <label for="cashAccount" class="block">Cash Account</label>
                <select wire:model="cashAccount"
                    class="w-full border rounded-lg p-2 @error('cashAccount') border-red-500 @enderror">
                    <option value="">--Pilih Account--</option>
                    @foreach ($cashAccounts as $c)
                    <option value="{{ $c->id }}">{{ $c->acc_name }}</option>
                    @endforeach
                </select>
                @error('cashAccount') <small class="text-red-500">{{ $message }}</small> @enderror
            </div>
            <div class="mb-2">
                <label for="address" class="block">Alamat</label>
                <textarea wire:model="address"
                    class="w-full border rounded-lg p-2 @error('address') border-red-500 @enderror"></textarea>
                @error('address') <small class="text-red-500">{{ $message }}</small> @enderror
            </div>

        </div>
        <div class="grid grid-cols-2 gap-2 mt-4">
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg">Simpan</button>
        </div>
    </form>
</div>