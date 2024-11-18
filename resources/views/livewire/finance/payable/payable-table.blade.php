<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title ?? __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-5 gap-3">
                    <div class="bg-white p-6 text-gray-900 rounded-2xl col-span-3">
                        <div class="mb-6 flex gap-2">
                            <x-modal modalName="createPayable" modalTitle="Form Input Hutang">
                                <livewire:finance.payable.create-payable />
                            </x-modal>
                            <button x-data x-on:click="$dispatch('open-modal', {'modalName': 'createPayable'})"
                                class="bg-sky-700 text-white min-w-36 sm:py-3 sm:px-8 p-6 text-xl sm:text-sm shadow-md flex justify-center items-center rounded-xl hover:bg-sky-800 transition duration-300 ease-out">
                                Input hutang &nbsp; <i class="fa-solid fa-plus-circle"></i>
                            </button>
                        </div>
                        <div class="mb-2">
                            <input type="search" class="w-full p-2 border rounded-xl" placeholder="Search .."
                                wire:model.live.debounce.500ms="search">
                        </div>
                        <table class="table-auto w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="p-4">Description</th>
                                    <th class="p-4">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payables as $p)
                                <tr class="border-b">
                                    <td class="-p2">{{ $p->description }}</td>
                                    <td class="text-end p-2">{{ number_format($p->bill_amount) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-white p-6 text-gray-900 rounded-2xl col-span-2">
                        <div class="bg-blue-800 text-white p-3 rounded-2xl mb-2">
                            <h4 class="text-sm">Total Hutang</h4>
                            <h1 class="font-bold text-3xl"><sup class="text-xs">Rp. </sup>200.000</h1>
                        </div>
                        <div class="mb-2">
                            <input type="search" class="w-full p-2 border rounded-xl" placeholder="Search .."
                                wire:model.live.debounce.500ms="search">
                        </div>
                        <table class="table-auto w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="p-4">Contact</th>
                                    <th class="p-4">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="-p2">Test</td>
                                    <td class="text-end p-2">200.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>