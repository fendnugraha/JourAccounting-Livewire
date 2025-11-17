<div class="mt-4">
    <div class="my-2">
        <x-text-input wire:model.live.debounce.500ms="search" type="text" class="block w-full"
            placeholder="Search..." />
    </div>
    <div class="overflow-x-auto">
        <table class="table w-full text-xs mb-2">
            <thead class="bg-white">
                <tr class="">
                    <th class="p-4">Name</th>
                    <th>Akun Kas</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($warehouses as $warehouse)
                <tr class="">
                    <td class="p-3 font-bold">ID:{{ $warehouse->id }} {{ $warehouse->name }}
                        <span class="block font-normal"> {{ $warehouse->code }} {{ $warehouse->address }}</span>
                    </td>
                    <td class="">{{ $warehouse->chartOfAccount->acc_name ?? 'Not Set' }}</td>
                    <td class="text-center">
                        <x-link href="/setting/warehouse/{{ $warehouse->id }}/edit"
                            class="text-slate-800 font-bold text-xs bg-yellow-400 py-2 px-5 rounded-lg hover:bg-yellow-300">
                            <i class="bi bi-pencil"></i>
                        </x-link>
                        <button wire:click="destroy({{ $warehouse->id }})" wire:loading.attr="disabled"
                            wire:confirm="Are you sure?"
                            class="text-white font-bold text-xs bg-red-400 py-2 px-5 rounded-lg hover:bg-red-300"><i
                                class="bi bi-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $warehouses->links() }}
    </div>

</div>