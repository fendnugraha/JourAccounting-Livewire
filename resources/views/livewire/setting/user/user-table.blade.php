<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-modal modalName="addUser" modalTitle="Form Tambah User">
                <livewire:setting.user.create-user />
            </x-modal>
            <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'addUser'})" bg-sky-950 text-w
                class="bg-blue-950 text-white rounded-lg py-2 px-3 mb-3">
                <i class="fa-solid fa-plus-circle"></i> Add new user
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
                                <th class=" p-3">ID</th>
                                <th class=" p-3">Email</th>
                                <th class=" p-3 hidden sm:table-cell">Created At</th>
                                <th class=" p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($users as $user)
                            <tr class="hover:bg-slate-100 border-slate-100 border-b">
                                <td class="text-center p-2">{{ $user->id }}</td>
                                <td class=" p-2">{{ $user->email }}<br><span class="text-slate-600">{{ $user->name
                                        }} | {{ $user->role->role ?? 'No Role Assigned' }} | {{
                                        $user->role->warehouse->name ?? 'No Role Assigned' }} </span></td>
                                <td class=" p-2 text-center hidden sm:table-cell">{{ $user->created_at }}</td>
                                <td class=" p-2 text-center">
                                    <a wire:navigate href="{{ route('user.edit', $user->id) }}"
                                        class="text-slate-800 font-bold block sm:inline sm:mb-0 mb-1 bg-yellow-400 py-2 px-5 rounded-lg hover:bg-yellow-300"><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                    <button wire:click="delete({{ $user->id }})" wire:loading.attr="disabled"
                                        wire:confirm="Are you sure?"
                                        class="text-white font-bold bg-red-400 py-2 px-5 rounded-lg hover:bg-red-300 w-full sm:w-auto"><i
                                            class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

</div>