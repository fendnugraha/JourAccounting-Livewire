<div class="mt-12 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="card p-4">
        <div class="flex justify-between">
            <h1 class="card-title mb-4">Penjualan Voucher
                <span class="card-subtitle">Total: {{ Number::format(-$voucher->sum('total_cost')) }}</span>
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
                <span class="card-subtitle">Total: {{ Number::format(-$voucher->sum('total_cost')) }}</span>
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