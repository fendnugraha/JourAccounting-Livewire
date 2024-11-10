<div class="">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-modal modalName="addAccount" modalTitle="Form Tambah Account">
                <livewire:setting.account.create-account />
            </x-modal>
            <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'addAccount'})" bg-sky-950 text-w
                class="bg-blue-950 text-white rounded-lg py-2 px-3 mb-3">
                <i class="fa-solid fa-plus-circle"></i> Add account
            </button>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="sm:p-6 p-2 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                    <div class="bg-green-500 text-white p-2 rounded-lg mb-3"><strong>Success!!</strong> {{
                        session('success') }}</div>
                    @endif
                    <h1 class="text-lg font-bold mb-3">Chart of Accounts</h1>
                    <div class="sm:w-1/2 mb-2">
                        <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search .."
                            class="w-full text-sm border rounded-lg p-2">
                    </div>
                    <table class="table-auto w-full text-sm mb-2">
                        <thead class="bg-white text-blue-950">
                            <tr class="border-b">
                                <th class="p-4">Account Name</th>
                                <th>Starting Balance</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($accounts as $account)
                            <tr class="hover:bg-slate-100 border-slate-100 border-b">
                                <td class=" p-2">
                                    {{ $account->acc_name }}<br>
                                    <span class="text-slate-600">{{ $account->acc_code }} #{{ $account->account->name
                                        }} #{{ $account->warehouse->name ?? 'NotAssociated' }}</span>
                                    <br>
                                    <div class="mt-2">
                                        <a href="/setting/account/{{ $account->id }}/edit" wire:navigate
                                            class="text-slate-800 font-bold text-xs bg-yellow-400 py-1 px-5 rounded-lg hover:bg-yellow-300"><i
                                                class="fa-solid fa-pen-to-square"></i></a>
                                        <button wire:click="delete({{ $account->id }})" wire:loading.attr="disabled"
                                            wire:confirm="Are you sure?"
                                            class="text-white font-bold text-xs bg-red-400 py-1 px-5 rounded-lg hover:bg-red-300"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </div>
                                </td>
                                <td class="text-right p-2 sm:text-lg">{{ number_format($account->st_balance, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $accounts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>