<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>
    @if(session('success'))
    <div class="bg-green-500 text-white p-2 rounded-lg mb-3"><strong>Success!!</strong> {{
        session('success') }}</div>
    @elseif (session('error'))
    <div class="bg-red-500 text-white p-2 rounded-lg mb-3"><strong>Error!!</strong> {{ session('error')
        }}</div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-modal modalName="addUser" modalTitle="Form Tambah Cabang">
                <livewire:setting.warehouse.create-warehouse />
            </x-modal>
            <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'addUser'})" bg-sky-950 text-w
                class="bg-blue-950 text-white rounded-lg py-2 px-3 mb-3">
                <i class="fa-solid fa-plus-circle"></i> Tambah cabang
            </button>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="sm:w-1/2 mb-2">
                        <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search .."
                            class="w-full text-sm border rounded-lg p-2">
                    </div>
                    <table class="table-auto w-full text-xs mb-2">
                        <thead class="bg-white text-blue-950">
                            <tr class="border-b">
                                <th class="p-3">Name</th>
                                <th class="">Status</th>
                                <th class="">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($warehouses as $warehouse)
                            <tr class="hover:bg-slate-100 border-slate-100 border-b">
                                <td class=" p-2">{{ $warehouse->name }}
                                    <br>
                                    <span class="text-slate-600">{{ $warehouse->code }} | {{ $warehouse->address
                                        }}</span>
                                </td>
                                <td class=" p-2 text-center">
                                    <small
                                        class="text-xs cursor-pointer hover:shadow-md px-2 py-1 rounded-lg {{ $warehouse->status == 1 ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}"
                                        wire:loading.attr="disabled" wire:click="updateStatus({{ $warehouse->id }})">{{
                                        $warehouse->status == 1 ?
                                        'Active' : 'Inactive'
                                        }}
                                    </small>
                                </td>
                                <td class="p-2 text-center">
                                    <a wire:navigate href="{{ route('warehouse.edit', $warehouse->id) }}"
                                        class="text-slate-800 block sm:inline mb-1 font-bold bg-yellow-400 py-1 px-5 rounded-lg hover:bg-yellow-300"><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                    <button wire:click="delete({{ $warehouse->id }})" wire:loading.attr="disabled"
                                        wire:confirm="Apakah anda yakin menghapus data ini?"
                                        class="text-white sm:w-auto w-full font-bold bg-red-400 py-1 px-5 rounded-lg hover:bg-red-300">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $warehouses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>