<div>
    <div>
        <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search .."
            class="w-full border rounded-lg p-2 mb-1">
    </div>
    <table class="table-auto w-full text-xs mb-2">
        <thead class="bg-white text-blue-950">
            <tr>
                <th class="p-3">Name</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @foreach ($banks as $bank)
            <tr class="border border-slate-100 odd:bg-white even:bg-blue-50">
                <td class="p-3">{{ $bank->acc_name }}</td>
                <td class="text-center">
                    <input type="checkbox" value="{{ $bank->id }}" wire:key="{{ $bank->id }}"
                        class="w-5 h-5 cursor-pointer accent-pink-600 disabled:bg-slate-200 disabled:cursor-not-allowed"
                        wire:click="updateBankList({{ $bank->id }}, {{ $warehouse->id }})" wire:loading.attr="disabled"
                        {{ $bank->warehouse_id == $warehouse->id ? 'checked' : '' }} {{
                    $bank->warehouse_id !== null &&
                    $bank->warehouse_id !== $warehouse->id ?
                    'disabled checked' : '' }}>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $banks->onEachSide(0)->links() }}
</div>