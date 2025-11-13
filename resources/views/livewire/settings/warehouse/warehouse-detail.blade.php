<div>
    <div class="my-2">
        <x-text-input wire:model.live.debounce.500ms="search" type="text" class="block w-full"
            placeholder="Search..." />
    </div>
    <div class="overflow-x-auto">
        <table class="table-auto w-full text-xs mb-2">
            <tbody class="bg-white">
                @foreach($accounts as $account)
                <tr class="border border-slate-100 odd:bg-white even:bg-blue-50" wire:key="account-{{ $account->id }}">
                    <td class="p-3">{{ $account->acc_name }}
                    </td>
                    <td class="text-center">
                        <input type="checkbox" class="disabled:bg-slate-500"
                            wire:click="updateAccountList({{ $warehouse->id }}, {{ $account->id }})"
                            class="form-checkbox" value="{{ $account->id }}" {{ $account->warehouse_id == $warehouse->id
                        ? 'checked' : '' }} {{
                        $account->id === $warehouse->chart_of_account_id ?
                        'disabled checked' : '' }} />
                    </td>


                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $accounts->links() }}

        <x-action-message class="ms-3" on="warehouse-updated">
            {{ session('success') ?? 'Updated.' }}
        </x-action-message>
    </div>
</div>