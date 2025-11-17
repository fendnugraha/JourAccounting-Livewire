<div class="mt-12">
    <div class="sm:flex gap-2 w-1/2 sm:col-span-2 h-fit mb-2">
        <div class="w-full flex justify-end gap-2 mb-2 sm:mb-0">
            @can('admin')
            <select class="form-select block w-fit p-2.5" wire:model.live="warehouse">
                <option value="all">Semua cabang</option>
                @foreach ($warehouses as $wh)
                <option value="{{ $wh->id }}" {{ $warehouse==$wh->id ? 'selected' : '' }}>{{
                    $wh->name }}</option>
                @endforeach
            </select>
            @endcan
            <button class="small-button" wire:click="$refresh">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
            <button class="small-button" x-data x-on:click="$dispatch('open-modal','filter-voucher')"><i
                    class=" bi bi-funnel"></i>
            </button>
            <x-modal name="filter-voucher" :show="false" :title="'Filter'" :maxWidth="'sm'">
                <div class="flex flex-col gap-2">
                    <x-input-label for="trx_type" :value="__('Dari')" />
                    <x-text-input wire:model.live="startDate" type="date" class="mt-1 block w-full" />
                    <x-input-label for="trx_type" :value="__('Sampai')" />
                    <x-text-input wire:model.live="endDate" type="date" class="mt-1 block w-full" />
                </div>
            </x-modal>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="card p-4">
            <div class="flex justify-between">
                <h1 class="card-title mb-4">Penjualan Voucher
                    <span class="card-subtitle">Periode: {{ $startDate }} sd {{ $endDate }}, Total: {{
                        Number::format(-$voucher->sum('total_cost')) }}</span>
                </h1>
                <select class="form-select h-fit !w-20 p-2.5" wire:model.live="voucherPerPage">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full text-xs">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Jual</th>
                            <th>Modal</th>
                            <th>Laba</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($voucher as $v)
                        <tr>
                            <td>{{ $v->product->name }}</td>
                            <td class="text-center">{{ Number::format(-$v->quantity) }}</td>
                            <td class="text-right">{{ number_format(-$v->total_price) }}</td>
                            <td class="text-right">{{ number_format(-$v->total_cost) }}</td>
                            <td class="text-right">{{ number_format(-$v->total_fee) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $voucher->links(data: ['scrollTo' => false]) }}
        </div>
        <div class="card p-4">
            <div class="flex justify-between">
                <h1 class="card-title mb-4">Penjualan Accessory
                    <span class="card-subtitle">Total: {{ Number::format(-$accessory->sum('total_cost')) }}</span>
                </h1>
                <select class="form-select h-fit !w-20 p-2.5" wire:model.live="accessoryPerPage">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full text-xs">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Jual</th>
                            <th>Modal</th>
                            <th>Laba</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accessory as $v)
                        <tr>
                            <td>{{ $v->product->name }}</td>
                            <td class="text-center">{{ Number::format(-$v->quantity) }}</td>
                            <td class="text-right">{{ number_format(-$v->total_price) }}</td>
                            <td class="text-right">{{ number_format(-$v->total_cost) }}</td>
                            <td class="text-right">{{ number_format(-$v->total_fee) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $accessory->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>