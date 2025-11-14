<div class="card h-[calc(100vh-233px)] p-2 sm:p-4 rounded-2xl sm:rounded-3xl mb-12">
    <div class="w-full flex justify-end gap-2 mb-4">
        <select class="form-select block w-fit p-2.5" wire:model.live="warehouse">
            <option value="all">Semua cabang</option>
            @foreach ($warehouses as $wh)
            <option value="{{ $wh->id }}" {{ $warehouse==$wh->id ? 'selected' : '' }}>{{
                $wh->name }}</option>
            @endforeach
        </select>
        <button class="small-button" wire:click="$refresh">
            <i class="bi bi-arrow-clockwise"></i>
        </button>
        <button class="small-button">
            <i class="bi bi-funnel"></i>
        </button>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-5 grid-row-1 sm:grid-rows-4 gap-4 grow h-fit">
        <div
            class="bg-lime-200/80 dark:bg-lime-500 text-green-900 dark:text-lime-800 p-3 sm:p-5 rounded-2xl sm:rounded-3xl drop-shadow-xs flex flex-col gap-2 sm:gap-4 items-start justify-between col-span-1 sm:col-span-2 row-span-1 sm:row-span-2">
            <div class="flex flex-col">
                <h4 class="text-lg">Kas Tunai</h4>
                <h1 class="text-2xl sm:text-4xl font-bold text-lime-700 dark:text-lime-800">
                    {{ Number::format($dailyReport['totalCash']) }}
                </h1>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                <div class="flex flex-col justify-between w-full bg-white/50 dark:bg-white/60 p-2 rounded-2xl">
                    <h4 class="text-sm">Bank</h4>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-500">
                        {{ Number::format($dailyReport['totalBank']) }}
                    </h1>
                </div>
                <div class="flex flex-col justify-between w-full bg-white/50 dark:bg-white/60 p-2 rounded-2xl">
                    <h4 class="text-sm">Total Uang</h4>
                    <h1 class="text-lg sm:text-xl font-semibold text-slate-500">
                        {{ Number::format($dailyReport['totalCash'] + $dailyReport['totalBank']) }}
                    </h1>
                </div>
            </div>
        </div>
        <div
            class="bg-teal-200/80 dark:bg-teal-400 text-green-900 p-3 sm:p-5 rounded-2xl sm:rounded-3xl drop-shadow-xs flex flex-col gap-2 sm:gap-4 items-start justify-between col-span-1 sm:col-span-2 row-span-auto sm:row-span-2 col-start-1 sm:col-start-3">

            <div class="flex flex-col">
                <h4 class="text-lg">
                    <GemIcon size={20} class="inline" /> Laba (Net Profit)
                </h4>
                <h1 class="text-2xl sm:text-4xl font-bold text-slate-500 dark:text-teal-800">
                    {{ Number::format($dailyReport['profit']) }}
                </h1>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                <div class="flex flex-col justify-between w-full bg-white/50 dark:bg-white/60 p-2 rounded-2xl">
                    <div class="flex items-start justify-between gap-2">
                        <h4 class="text-sm text-nowrap overflow-hidden">Tranfer Uang</h4>
                        <h4
                            class="text-sm font-semibold px-2 py-0.5 bg-teal-200/40 dark:bg-slate-500 text-slate-500 dark:text-white rounded-full">
                            {{ Number::format($dailyReport['totalTransfer']['count']) }}
                        </h4>
                    </div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-500">
                        {{ Number::format($dailyReport['totalTransfer']['total']) }}
                    </h1>
                </div>
                <div class="flex flex-col justify-between w-full bg-white/50 dark:bg-white/60 p-2 rounded-2xl">
                    <div class="flex items-start justify-between gap-2">
                        <h4 class="text-sm text-nowrap">Tarik Tunai</h4>
                        <h4
                            class="text-sm font-semibold px-2 py-0.5 bg-teal-200/40 dark:bg-slate-500 text-slate-500 dark:text-white rounded-full">
                            {{ Number::format($dailyReport['totalCashWithdrawal']['count']) }}
                        </h4>
                    </div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-500">
                        {{ Number::format($dailyReport['totalCashWithdrawal']['total']) }}
                    </h1>
                </div>
            </div>
        </div>
        <div
            class="col-start-1 sm:col-start-5 row-span-4 rounded-2xl flex flex-col gap-2 sm:gap-4 justify-between items-center">
            <div
                class="bg-violet-200 sm:rounded-3xl drop-shadow-xs h-full flex flex-col justify-center items-center w-full">
                <h1 class="text-xl sm:text-2xl font-bold text-violet-500">
                    {{ Number::format($dailyReport['totalRevenue']) }}
                </h1>
                <h1 class="text-slate-500">Total Setoran</h1>
            </div>
            <div
                class="bg-orange-200 rounded-2xl sm:rounded-3xl drop-shadow-xs h-full flex flex-col justify-center items-center w-full">

                <h1 class="text-xl sm:text-2xl font-bold text-orange-500">
                    {{ Number::format($dailyReport['totalFee']) }}
                </h1>
                <h1 class="text-slate-500">Fee (Admin)</h1>
            </div>
            <div
                class="w-full bg-red-200 rounded-2xl sm:rounded-3xl drop-shadow-xs h-full flex flex-col justify-center items-center">

                <h1 class="text-xl sm:text-2xl font-bold text-red-500">
                    {{ Number::format(-$dailyReport['totalExpense']) }}</h1>
                <h1 class="text-slate-500">Biaya</h1>
            </div>
            <div
                class="w-full bg-blue-200 rounded-2xl sm:rounded-3xl drop-shadow-xs h-full flex flex-col justify-center items-center">

                <h1 class="text-xl sm:text-2xl font-bold text-blue-500">
                    {{ Number::format($dailyReport['salesCount']) }}
                </h1>
                <h1 class="text-slate-500">Transaksi</h1>
            </div>
        </div>

        <div
            class="col-span-1 sm:col-span-4 row-span-1 sm:row-span-2 col-start-1 row-start-auto sm:row-start-3 flex flex-col sm:flex-row gap-4 items-center justify-between w-full h-full">
            <div
                class="bg-slate-200 dark:bg-yellow-200 w-full h-full rounded-2xl sm:rounded-3xl drop-shadow-xs p-3 sm:p-5 flex flex-col justify-between">
                <div>
                    <h1 class="text-lg text-slate-500">Voucher & SP</h1>
                    <TicketIcon size={50} strokeWidth={1.5} class="text-slate-500" />
                </div>
                <div>
                    <h1 class="text-sm bg-slate-500/50 px-2 rounded-full w-fit font-semibold text-white">
                        {{ Number::format($dailyReport['totalVoucher']['count']) }}
                    </h1>
                    <h1 class="text-2xl sm:text-4xl font-bold text-slate-500">
                        {{ Number::format($dailyReport['totalVoucher']['total']) }}
                    </h1>
                </div>
            </div>
            <div
                class="bg-slate-200 dark:bg-yellow-200 w-full h-full rounded-2xl sm:rounded-3xl drop-shadow-xs p-3 sm:p-5 flex flex-col justify-between">
                <div>
                    <h1 class="text-lg text-slate-500">Accessories</h1>
                    <CableIcon size={50} strokeWidth={1.5} class="text-slate-500" />
                </div>
                <div>
                    <h1 class="text-sm bg-slate-500/50 px-2 rounded-full w-fit font-semibold text-white">
                        {{ Number::format($dailyReport['totalAccessories']['count']) }}
                    </h1>
                    <h1 class="text-2xl sm:text-4xl font-bold text-slate-500">
                        {{ Number::format($dailyReport['totalAccessories']['total']) }}
                    </h1>
                </div>
            </div>
            <div
                class="bg-slate-200 dark:bg-yellow-200 w-full h-full rounded-2xl sm:rounded-3xl drop-shadow-xs p-3 sm:p-5 flex flex-col justify-between">
                <div>

                    <h1 class="text-lg text-slate-500">Deposit (Pulsa, Token, Dll)</h1>
                    <SmartphoneIcon size={50} strokeWidth={1.5} class="text-slate-500" />
                </div>
                <div>
                    <h1 class="text-sm bg-slate-500/50 px-2 rounded-full w-fit font-semibold text-white">
                        {{ Number::format($dailyReport['totalCashDeposit']['count']) }}
                    </h1>
                    <h1 class="text-2xl sm:text-4xl font-bold text-slate-500">
                        {{ Number::format($dailyReport['totalCashDeposit']['total']) }}
                    </h1>
                </div>
            </div>
        </div>
    </div>
</div>