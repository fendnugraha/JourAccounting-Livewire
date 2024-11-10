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
                            <h1 class="text-6xl sm:text-8xl font-bold text-center"><i class="fa-solid fa-user"></i>
                            </h1>
                            <a href="{{ route('user.index') }}"
                                class="sm:sm:absolute hover:underline hover:font-bold bottom-4 right-5 text-sm rounded-full"
                                wire:navigate>User <span class="hidden sm:inline">Management</span>
                                <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div
                            class="sm:relative bg-white p-3 sm:p-6 dark:bg-gray-800 overflow-hidden shadow-md rounded-xl h-32 sm:h-60 flex sm:justify-center justify-evenly items-center flex-col gap-1">
                            <h1 class="text-5xl sm:text-8xl font-bold text-center"><i
                                    class="fa-solid fa-scale-balanced"></i>
                            </h1>
                            <a href="{{ route('account') }}"
                                class="sm:sm:absolute hover:underline hover:font-bold bottom-4 right-5 text-sm rounded-full"
                                wire:navigate>Account <span class="hidden sm:inline">Management</span>
                                <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div
                            class="sm:relative bg-white p-3 sm:p-6 dark:bg-gray-800 overflow-hidden shadow-md rounded-xl h-32 sm:h-60 flex sm:justify-center justify-evenly items-center flex-col gap-1">
                            <h1 class="text-5xl sm:text-8xl font-bold text-center">
                                <i class="fa-solid fa-warehouse"></i>
                            </h1>
                            <a href="{{ route('warehouse') }}"
                                class="sm:absolute hover:underline hover:font-bold bottom-4 right-5 text-sm rounded-full"
                                wire:navigate>Warehouse <span class="hidden sm:inline">Management</span>
                                <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div
                            class="sm:relative bg-white p-3 sm:p-6 dark:bg-gray-800 overflow-hidden shadow-md rounded-xl h-32 sm:h-60 flex sm:justify-center justify-evenly items-center flex-col gap-1">
                            <h1 class="text-5xl sm:text-8xl font-bold text-center">
                                <i class="fa-solid fa-address-book"></i>
                            </h1>
                            <a href="{{ route('contact') }}"
                                class="sm:absolute hover:underline hover:font-bold bottom-4 right-5 text-sm rounded-full"
                                wire:navigate>Contact <span class="hidden sm:inline">Management</span>
                                <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div
                            class="sm:relative bg-white p-3 sm:p-6 dark:bg-gray-800 overflow-hidden shadow-md rounded-xl h-32 sm:h-60 flex sm:justify-center justify-evenly items-center flex-col gap-1">
                            <h1 class="text-5xl sm:text-8xl font-bold text-center">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </h1>
                            <a href="{{ route('product') }}"
                                class="sm:absolute hover:underline hover:font-bold bottom-4 right-5 text-sm rounded-full"
                                wire:navigate>Product <span class="hidden sm:inline">Management</span>
                                <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        {{-- <a
                            class="text-2xl bg-sky-300 text-sky-950 p-4 shadow-300 h-30 sm:h-60 flex justify-center items-center rounded-xl hover:bg-sky-400 hover:text-5xl transition-all delay-300 duration-300 ease-out"
                            href="{{ route('user.index') }}" wire:navigate>User
                        </a>
                        <a class="text-2xl bg-sky-300 text-sky-950 p-4 shadow-300 h-30 sm:h-60 flex justify-center items-center rounded-xl hover:bg-sky-400 hover:text-5xl transition-all delay-300 duration-300 ease-out"
                            href="{{ route('account') }}" wire:navigate>Account
                        </a>
                        <a class="text-2xl bg-sky-300 text-sky-950 p-4 shadow-300 h-30 sm:h-60 flex justify-center items-center rounded-xl hover:bg-sky-400 hover:text-5xl transition-all delay-300 duration-300 ease-out"
                            href="{{ route('warehouse') }}" wire:navigate>Warehouse
                        </a>
                        <a class="text-2xl bg-sky-300 text-sky-950 p-4 shadow-300 h-30 sm:h-60 flex justify-center items-center rounded-xl hover:bg-sky-400 hover:text-5xl transition-all delay-300 duration-300 ease-out"
                            href="{{ route('contact') }}" wire:navigate>Contact
                        </a>
                        <a class="text-2xl bg-sky-300 text-sky-950 p-4 shadow-300 h-30 sm:h-60 flex justify-center items-center rounded-xl hover:bg-sky-400 hover:text-5xl transition-all delay-300 duration-300 ease-out"
                            href="{{ route('product') }}" wire:navigate>Product
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>