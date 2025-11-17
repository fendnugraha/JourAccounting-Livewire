<div class="mt-4">
    <div class="my-2">
        <x-text-input wire:model.live.debounce.500ms="search" type="text" class="block w-full"
            placeholder="Search..." />
    </div>
    <div class="overflow-x-auto">
        <table class="table w-full text-xs mb-2">
            <thead class="bg-white text-blue-950">
                <tr class="border-b">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Cabang</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($users as $user)
                <tr class="">
                    <td>ID: {{ $user->id }} {{ $user->name }}
                        <span class="block">{{ $user->roles->role ?? 'Not Set' }}</span>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td class="text-center">{{ $user->roles->warehouse->name ?? 'Not Set' }}</td>
                    <td class="text-center">
                        <button
                            class="text-white font-bold text-xs bg-yellow-400 py-2 px-5 rounded-lg hover:bg-yellow-300"
                            wire:click="setUserId({{ $user->id }})"><i class="bi bi-pencil"></i></button>
                        <button wire:click="destroy({{ $user->id }})" wire:loading.attr="disabled"
                            wire:confirm="Are you sure?"
                            class="text-white font-bold text-xs bg-red-400 py-2 px-5 rounded-lg hover:bg-red-300"><i
                                class="bi bi-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
    <x-modal name="edit-user" :show="false" :title="'Edit User'">
        @livewire('settings.user.edit-user', ['user_id' => $user_id])
    </x-modal>
</div>