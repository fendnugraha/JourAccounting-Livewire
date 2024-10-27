<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex gap-2 justify-between mb-2">
                        <div class="flex gap-2">
                            <x-modal modalName="addUser" modalTitle="Form Tambah User">
                                <livewire:pages.auth.register />
                            </x-modal>
                            <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'addUser'})" bg-sky-950
                                text-w class="bg-blue-950 text-white rounded-lg py-1 px-3 h-full">
                                <i class="fa-solid fa-plus"></i> Add User
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
                                <th class="border border-slate-200 p-3">ID</th>
                                <th class="border border-slate-200 p-3">Name</th>
                                <th class="border border-slate-200 p-3">Email</th>
                                <th class="border border-slate-200 p-3">Role</th>
                                <th class="border border-slate-200 p-3">Created At</th>
                                <th class="border border-slate-200 p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($users as $user)
                            <tr class="border border-slate-100 odd:bg-white even:bg-blue-50">
                                <td class="border border-slate-200 p-2">{{ $user->id }}</td>
                                <td class="border border-slate-200 p-2">{{ $user->name }}</td>
                                <td class="border border-slate-200 p-2">{{ $user->email }}</td>
                                <td class="border border-slate-200 p-2">{{ $user->role->role ?? 'No Role Assigned' }}
                                </td>
                                <td class="border border-slate-200 p-2 text-center">{{ $user->created_at }}</td>
                                <td class="border border-slate-200 p-2 text-center">
                                    <a href="/setting/user/{{ $user->id }}/edit"
                                        class="text-slate-800 font-bold bg-yellow-400 py-2 px-5 rounded-lg hover:bg-yellow-300">Edit</a>
                                    <button wire:click="delete({{ $user->id }})" wire:loading.attr="disabled"
                                        wire:confirm="Are you sure?"
                                        class="text-white font-bold bg-red-400 py-2 px-5 rounded-lg hover:bg-red-300">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>