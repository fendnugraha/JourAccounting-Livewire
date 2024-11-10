<div class="bg-gray-300 dark:bg-gray-700 flex gap-1 mb-3 p-1 rounded-xl">
    @foreach($links as $link)
    <a href="{{ $link['href'] }}" wire:navigate class="{{ request()->is($link['route']) ? 'bg-white dark:bg-gray-800' : '' }} 
                  text-gray-900 text-sm dark:text-gray-100 w-40 text-center 
                  hover:bg-white hover:underline px-2 py-1 rounded-lg 
                  transition duration-150 ease-in-out">
        {{ $link['text'] }}
    </a>
    @endforeach
</div>