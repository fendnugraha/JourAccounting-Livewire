<div class="">
    @if(session('success'))
    <div class="bg-green-500 text-white p-2 rounded-lg mb-3"><strong>Success!!</strong> {{ session('success') }}</div>
    @endif

    <div class="flex gap-2 justify-between">
        <div class="flex gap-2">
            <x-modal modalName="addAccount" modalTitle="Form Tambah Account">
                <livewire:account.create-account />
            </x-modal>
            <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'addAccount'})" bg-sky-950 text-w
                class="bg-blue-950 text-white rounded-lg py-1 px-3 h-full">
                <i class="fa-solid fa-plus"></i> Add Account
            </button>
            <div>
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search .."
                    class="w-full text-sm border rounded-lg p-2">
            </div>
        </div>
        <a href="/setting" class="bg-red-700 py-2 px-6 text-sm rounded-lg text-white hover:bg-sky-700">
            Kembali
        </a>
    </div>

    <table class="table-auto w-full text-xs mb-2">
        <thead class="bg-white text-blue-950">
            <tr class="border-b">
                <th class="p-4">ID</th>
                <th>Code</th>
                <th>Name</th>
                <th>Type</th>
                <th>Starting Balance</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @foreach($accounts as $account)
            <tr class="border border-slate-100 odd:bg-white even:bg-blue-50">
                <td class="p-3">{{ $account->id }}</td>
                <td>{{ $account->acc_code }}</td>
                <td>{{ $account->acc_name }}</td>
                <td>{{ $account->account->name }}</td>
                <td class="text-right">{{ number_format($account->st_balance, 2) }}</td>
                <td class="text-center">
                    <a href="/setting/account/{{ $account->id }}/edit"
                        class="text-slate-800 font-bold text-xs bg-yellow-400 py-2 px-5 rounded-lg hover:bg-yellow-300">Edit</a>
                    <button wire:click="delete({{ $account->id }})" wire:loading.attr="disabled"
                        wire:confirm="Are you sure?"
                        class="text-white font-bold text-xs bg-red-400 py-2 px-5 rounded-lg hover:bg-red-300">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $accounts->links() }}
</div>