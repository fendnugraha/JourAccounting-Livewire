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
    <div class="bg-violet-500 text-white p-2 rounded-lg mb-3 w-full" wire:loading>
        Menyimpan data ..
    </div>
    <form wire:submit="save">
        <div class="">
            <div class="mb-2">
                <label for="account" class="block">Type Account</label>
                <select wire:model="account"
                    class="w-full border rounded-lg p-2 @error('account') border-red-500 @enderror" autofocus>
                    <option value="">--Pilih Type Account--</option>
                    @foreach ($accounts as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('account') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-2">
                <label for="name" class="block">Nama Account</label>
                <input type="text" wire:model="name"
                    class="w-full border rounded-lg p-2 @error('name') border-red-500 @enderror">
                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-2">
                <label for="st_balance" class="block">Saldo Awal</label>
                <input type="number" wire:model="st_balance"
                    class="w-full border rounded-lg p-2 @error('st_balance') border-red-500 @enderror">
                @error('st_balance') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

        </div>
        <div class="grid grid-cols-2 gap-2 mt-4">
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg">Simpan</button>
        </div>
    </form>


</div>