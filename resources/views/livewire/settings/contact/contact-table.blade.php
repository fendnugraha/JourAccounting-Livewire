<div class="mt-4 w-1/2">
    <div class="my-2">
        <x-text-input wire:model.live.debounce.500ms="search" type="text" class="block w-full"
            placeholder="Search..." />
    </div>
    <div class="overflow-x-auto">
        <table class="table w-full text-xs mb-2">
            <thead class="bg-white text-blue-950">
                <tr class="border-b">
                    <th class="p-4">Name</th>
                    <th>Description</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($contacts as $contact)
                <tr class="">
                    <td class="p-3">{{ $contact->name }}
                        <span class="block">{{ $contact->type }}</span>
                    </td>
                    <td>{{ $contact->description }}</td>
                    <td class="text-center">
                        <a href="/setting/warehouse/{{ $contact->id }}/edit"
                            class="text-slate-800 font-bold text-xs bg-yellow-400 py-2 px-5 rounded-lg hover:bg-yellow-300">Edit</a>
                        <button wire:click="destroy({{ $contact->id }})" wire:loading.attr="disabled"
                            wire:confirm="Are you sure?"
                            class="text-white font-bold text-xs bg-red-400 py-2 px-5 rounded-lg hover:bg-red-300">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $contacts->links() }}
    </div>
</div>