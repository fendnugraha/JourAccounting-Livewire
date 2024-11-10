<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>
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
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-2 justify-between mb-3 sm:w-1/2">
                <div class="flex gap-2">
                    <x-modal modalName="addContact" modalTitle="Form Tambah Kontak">
                        <livewire:setting.contact.create-contact />
                    </x-modal>
                    <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'addContact'})" bg-sky-950 text-w
                        class="bg-blue-950 hover:bg-blue-900 text-white rounded-lg py-1 px-3 h-full">
                        <i class="fa-solid fa-plus-circle"></i> Tambah kontak
                    </button>
                    <div>
                        <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search .."
                            class="w-full text-sm border rounded-lg p-2">
                    </div>
                </div>
                <a wire:navigate href="/setting"
                    class="bg-red-700 py-2 px-6 text-sm rounded-lg text-white hover:bg-red-600">
                    Kembali
                </a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sm:w-1/2">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="table-auto w-full text-xs mb-2">
                        <thead class="bg-white text-blue-950">
                            <tr class="border-b">
                                <th class="p-4">ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($contacts as $contact)
                            <tr class="hover:bg-slate-100 border-slate-100 border-b">
                                <td class="text-center p-3">{{ $contact->id }}</td>
                                <td>{{ $contact->name }}<br><span class="text-slate-600">{{ $contact->type }}</span>
                                </td>
                                <td class="text-right">{{ $contact->description }}</td>
                                <td class="text-center">
                                    <a wire:navigate href="/setting/contact/{{ $contact->id }}/edit"
                                        class="text-slate-800 font-bold bg-yellow-400 py-1 px-3 rounded-lg hover:bg-yellow-300"><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                    <button wire:click="delete({{ $contact->id }})" wire:loading.attr="disabled"
                                        wire:confirm="Apakah anda yakin menghapus data ini?"
                                        class="text-white font-bold bg-red-400 py-1 px-3 rounded-lg hover:bg-red-300"><i
                                            class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $contacts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>