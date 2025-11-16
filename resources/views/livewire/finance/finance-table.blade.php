<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="card p-4">
        <h1 class="card-title mb-4">Finansial
            <span class="card-subtitle">Total {{ $finance_type == 'Receivable' ? 'Piutang' : 'Hutang' }} : {{
                Number::format($this->finances->sum('sisa')) }}</span>
        </h1>
        <div class="overflow-x-auto">
            <table class="table w-full text-xs">
                <thead>
                    <tr>
                        <th class="text-center">Contact</th>
                        <th class="text-center">Sisa</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->finances as $finance)
                    <tr>
                        <td>
                            <button class="hover:underline" wire:click="changeContact({{ $finance->contact_id }})">{{
                                $finance->contact->name }}</button>
                        </td>
                        <td class="text-right text-sm">{{ Number::format($finance->sisa) }}</td>
                        <td class="text-center">
                            <button>Bayar</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @livewire('finance.finance-detail', ['contact' => $contact, 'finance_type' => $finance_type])
</div>