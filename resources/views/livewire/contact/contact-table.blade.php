<div>
    @if(session('success'))
    <x-notification>
        <x-slot name="classes">bg-green-500 text-white absolute bottom-3 left-4</x-slot>
        <strong>Success!!</strong> {{ session('success') }}
    </x-notification>
    @elseif (session('error'))
    <x-notification>
        <x-slot name="classes">bg-red-500 text-white absolute bottom-3 left-4</x-slot>
        <strong>Error!!</strong> {{
        session('error') }}
    </x-notification>
    @endif

    <div class="flex gap-2 justify-between mb-3">
        <div class="flex gap-2">
            <x-modal modalName="addContact" modalTitle="Form Tambah Kontak">
                <livewire:contact.create-contact />
            </x-modal>
            <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'addContact'})" bg-sky-950 text-w
                class="bg-blue-950 text-white rounded-lg py-1 px-3 h-full">
                <i class="fa-solid fa-plus"></i> Tambah Kontak
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
                <th class="p-4">ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Action</th>

            </tr>
        </thead>

        <tbody class="bg-white">
            @foreach ($contacts as $contact)
            <tr class="border border-slate-100 odd:bg-white even:bg-blue-50">
                <td class="p-3">{{ $contact->id }}</td>
                <td>{{ $contact->name }}</td>
                <td class="text-right">{{ $contact->type }}</td>
                <td class="text-right">{{ $contact->description }}</td>
                <td class="text-center">
                    <a href="/setting/contact/{{ $contact->id }}/edit"
                        class="text-slate-800 font-bold bg-yellow-400 py-1 px-3 rounded-lg hover:bg-yellow-300">Edit</a>
                    <button wire:click="delete({{ $contact->id }})" wire:loading.attr="disabled"
                        wire:confirm="Apakah anda yakin menghapus data ini?"
                        class="text-white font-bold bg-red-400 py-1 px-3 rounded-lg hover:bg-red-300">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $contacts->links() }}
</div>