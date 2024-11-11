<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4">
                        <div
                            class="sm:relative bg-white p-3 sm:p-6 dark:bg-gray-800 overflow-hidden shadow-md rounded-xl h-32 sm:h-60 flex sm:justify-center justify-evenly items-center flex-col gap-1">
                            <h1 class="text-6xl sm:text-8xl font-bold text-center"><i
                                    class="fa-solid fa-chart-line"></i>
                            </h1>
                            <a href="{{ route('cashflow') }}"
                                class="sm:sm:absolute hover:underline hover:font-bold bottom-4 right-5 text-sm rounded-full"
                                wire:navigate>Cashflow <span class="hidden sm:inline">(Arus Kas)</span>
                                <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div
                            class="sm:relative bg-white p-3 sm:p-6 dark:bg-gray-800 overflow-hidden shadow-md rounded-xl h-32 sm:h-60 flex sm:justify-center justify-evenly items-center flex-col gap-1">
                            <h1 class="text-5xl sm:text-8xl font-bold text-center"><i
                                    class="fa-solid fa-scale-balanced"></i>
                            </h1>
                            <a href="{{ route('balance-sheet') }}"
                                class="sm:sm:absolute hover:underline hover:font-bold bottom-4 right-5 text-sm rounded-full"
                                wire:navigate>Balance Sheet <span class="hidden sm:inline">(Neraca)</span>
                                <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div
                            class="sm:relative bg-white p-3 sm:p-6 dark:bg-gray-800 overflow-hidden shadow-md rounded-xl h-32 sm:h-60 flex sm:justify-center justify-evenly items-center flex-col gap-1">
                            <h1 class="text-5xl sm:text-8xl font-bold text-center">
                                <i class="fa-solid fa-sack-dollar"></i>
                            </h1>
                            <a href="{{ route('profit-loss') }}"
                                class="sm:absolute hover:underline hover:font-bold bottom-4 right-5 text-sm rounded-full"
                                wire:navigate>Profit Loss <span class="hidden sm:inline">(Laba Rugi)</span>
                                <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div
                            class="sm:relative bg-white p-3 sm:p-6 dark:bg-gray-800 overflow-hidden shadow-md rounded-xl h-32 sm:h-60 flex sm:justify-center justify-evenly items-center flex-col gap-1">
                            <h1 class="text-5xl sm:text-8xl font-bold text-center">
                                <i class="fa-solid fa-book-open"></i>
                            </h1>
                            <a href="{{ route('general-ledger') }}"
                                class="sm:absolute hover:underline hover:font-bold bottom-4 right-5 text-sm rounded-full"
                                wire:navigate>General Ledger <span class="hidden sm:inline">(Buku Besar)</span>
                                <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>