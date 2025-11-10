@props([
'message' => null,
'on' => 'saved',
'type' => 'success'
])

<div x-data="{ shown: false, timeout: null }" x-init="
        @if ($message)
            shown = true;
            timeout = setTimeout(() => { shown = false }, 3000);
        @endif
    " x-show.transition.out.opacity.duration.1500ms="shown" x-transition:leave.opacity.duration.1500ms
    style="display: none;" {{ $attributes->merge(['class' => 'bg-white rounded-md py-2 px-3 drop-shadow text-sm
    text-gray-600 fixed top-3 right-3 mt-4 mr-4 z-[999]']) }}
    >
    {{ $message ?? $type }}
</div>