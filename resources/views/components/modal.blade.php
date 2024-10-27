@props(['modalName', 'modalTitle'])

<div x-data="{ isModalOpen: false, modalName: '{{ $modalName }}', modalTitle: '{{ $modalTitle }}' }"
    x-show="isModalOpen" x-on:open-modal.window="isModalOpen = ($event.detail.modalName === modalName)"
    x-on:close-modal.window="isModalOpen = false" x-on:keydown.escape.window="isModalOpen = false"
    class="fixed inset-0 z-50 text-sm" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" style="display: none">
    <div x-on:click="isModalOpen = false" class="fixed inset-0 bg-gray-500/75
    "></div>
    <div
        class="bg-slate-100/90 backdrop-blur-md rounded-lg m-auto fixed inset-0 w-full sm:w-3/4 lg:w-1/2 overflow-y-auto h-fit">
        <div class="flex justify-between items-center p-4 border-b">
            <h5 class="font-bold text-lg">{{ $modalTitle }}</h5>
            <button x-on:click="isModalOpen = false" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M18.364 5.636c.78.78.78 2.048 0 2.828L14.828 12l3.536 3.536c.78.78.78 2.048 0 2.828-.78.78-2.048.78-2.828 0L12 14.828l-3.536 3.536c-.78.78-2.048.78-2.828 0-.78-.78-.78-2.048 0-2.828L9.172 12 5.636 8.464c-.78-.78-.78-2.048 0-2.828s2.048-.78 2.828 0L12 9.172l3.536-3.536c.78-.78 2.048-.78 2.828 0z" />
                </svg>
            </button>
        </div>
        <div class="p-3">
            {{ $slot }}
        </div>
    </div>
</div>