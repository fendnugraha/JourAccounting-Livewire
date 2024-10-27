<x-app-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 p-4">
        <a class="text-2xl bg-sky-300 text-sky-950 p-4 shadow-300 h-30 sm:h-60 flex justify-center items-center rounded-xl hover:bg-sky-400 hover:text-5xl transition-all delay-300 duration-300 ease-out"
            href="/setting/user">User
        </a>
        <a class="text-2xl bg-sky-300 text-sky-950 p-4 shadow-300 h-30 sm:h-60 flex justify-center items-center rounded-xl hover:bg-sky-400 hover:text-5xl transition-all delay-300 duration-300 ease-out"
            href="/setting/account">Account
        </a>
        <a class="text-2xl bg-sky-300 text-sky-950 p-4 shadow-300 h-30 sm:h-60 flex justify-center items-center rounded-xl hover:bg-sky-400 hover:text-5xl transition-all delay-300 duration-300 ease-out"
            href="/setting/warehouse">Warehouse
        </a>
        <a class="text-2xl bg-sky-300 text-sky-950 p-4 shadow-300 h-30 sm:h-60 flex justify-center items-center rounded-xl hover:bg-sky-400 hover:text-5xl transition-all delay-300 duration-300 ease-out"
            href="/setting/contact">Contact
        </a>
        <a class="text-2xl bg-sky-300 text-sky-950 p-4 shadow-300 h-30 sm:h-60 flex justify-center items-center rounded-xl hover:bg-sky-400 hover:text-5xl transition-all delay-300 duration-300 ease-out"
            href="/setting/product">Product
        </a>
    </div>
</x-app-layout>