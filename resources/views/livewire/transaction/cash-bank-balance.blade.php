<div class="">
    <div
        class="flex justify-center items-center mb-3 flex-col bg-sky-800 hover:bg-sky-900 p-2 rounded-2xl text-orange-300 hover:text-white">
        <h1 class="text-sm">Total Saldo Kas & Bank</h1>
        <h1 class="text-2xl font-bold">{{ number_format($total) }}</h1>

    </div>
    <div class="rounded-lg">
        @foreach ($accounts as $account)
        <div class="mb-2">
            <div
                class="flex flex-col hover:scale-105 justify-between py-2 px-4 rounded-2xl shadow-sm hover:shadow-lg bg-orange-200 hover:bg-orange-300 transition duration-150 ease-out">
                <h1 class="text-xs">{{ $account->acc_name }}</h1>

                <h1 class="text-lg font-bold text-end">{{
                    number_format($account->balance) }}</h1>
            </div>
        </div>
        @endforeach
    </div>
</div>