<div class="mt-4">
    <div class="my-2">
        <x-text-input wire:model.live.debounce.500ms="search" type="text" class="block w-full"
            placeholder="Search..." />
    </div>
    <div class="overflow-x-auto">
        <table class="table w-full text-xs mb-2">
            <thead class="bg-white text-blue-950">
                <tr class="">
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
                <tr>
                    <td class="p-3">{{ $account->id }}</td>
                    <td>{{ $account->acc_code }}</td>
                    <td>{{ $account->acc_name }}</td>
                    <td>{{ $account->account->name }}</td>
                    <td class="text-right">{{ number_format($account->st_balance, 2) }}</td>
                    <td class="text-center">
                        <a href="/setting/account/{{ $account->id }}/edit"
                            class="text-slate-800 font-bold text-xs bg-yellow-400 py-2 px-5 rounded-lg hover:bg-yellow-300">Edit</a>
                        <button wire:click="destroy({{ $account->id }})" wire:loading.attr="disabled"
                            wire:confirm="Are you sure?"
                            class="text-white font-bold text-xs bg-red-400 py-2 px-5 rounded-lg hover:bg-red-300">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $accounts->links() }}
    </div>
</div>