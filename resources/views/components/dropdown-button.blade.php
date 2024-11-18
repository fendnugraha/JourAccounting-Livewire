@props(['dropdownName', 'dropdownTitle'])

<div class="relative" x-data="{ isDropdownOpen: false }" x-on:click.outside="isDropdownOpen = false">
    <div>
        <button x-on:click="isDropdownOpen = !isDropdownOpen" {{ $attributes->merge(['class' => '']) }}>
            {{ $dropdownTitle }} &nbsp;<i class="fa-solid fa-caret-down"></i>
        </button>
    </div>
    <div x-show="isDropdownOpen" x-transition.origin.top.left class="absolute sm:top-10 sm:w-60 w-full z-10">
        <div class="bg-white rounded-md shadow-lg">
            {{ $slot }}
        </div>
    </div>
</div>