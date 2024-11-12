<div x-data="{ showNotification: true }" x-init="setTimeout(() => { showNotification = false; }, 5000)"
    class="fixed top-20 min-w-72 right-2 sm:right-16">
    <div x-show="showNotification" {{ $attributes->merge(['class' => 'px-4
        py-2
        rounded-lg shadow-md z-50']) }}>
        {{ $slot }}
    </div>
</div>