<div class="mt-4">
    <div class="my-2">
        <x-text-input wire:model.live.debounce.500ms="search" type="text" class="block w-full"
            placeholder="Search..." />
    </div>
    <div class="overflow-x-auto">
        <table class="table w-full text-xs mb-2">
            <thead class="bg-white text-blue-950">
                <tr class="">
                    <th>Name</th>
                    <th>Type</th>
                    <th>Starting Balance</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($accounts as $account)
                <tr>
                    <td class="font-bold">{{ $account->acc_name }}
                        <span class="block font-normal">ID:{{ $account->id }}, {{ $account->acc_code }}, {{
                            $account->warehouse->name ?? 'Not Assigned' }}</span>
                    </td>
                    <td>{{ $account->account->name }}</td>
                    <td class="text-right text-lg">{{ number_format($account->st_balance) }}</td>
                    <td class="text-center">
                        <button wire:click="select({{ $account->id }})"
                            class="text-slate-800 font-bold text-xs bg-yellow-400 py-2 px-5 rounded-lg hover:bg-yellow-300"><i
                                class="bi bi-pencil-square"></i></button>
                        <button wire:click="destroy({{ $account->id }})" wire:loading.attr="disabled"
                            wire:confirm="Are you sure?"
                            class="text-white font-bold text-xs bg-red-400 py-2 px-5 rounded-lg hover:bg-red-300"><i
                                class="bi bi-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $accounts->links() }}
    </div>
    <x-modal name="edit-account" :show="false" :title="'Tambah Account'">
        @livewire('settings.account.edit-account', ['id' => $selectedId])
    </x-modal>
</div>