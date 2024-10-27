<div x-data="{ showNotification: true }" x-init="setTimeout(() => { showNotification = false; }, 5000)"
    class="fixed bottom-1 right-2">
    <div x-show="showNotification" {{ $attributes->merge(['class' => 'px-4
        py-2
        rounded-lg shadow-md z-50']) }}>
        {{ $slot }}
    </div>
</div>