<?php

namespace App\Livewire\Settings\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        return view('livewire.settings.product.product-table', [
            'products' => Product::with('category:id,name')->where('name', 'like', '%' . $this->search . '%')->orderBy('name')->paginate(10)->onEachSide(0)
        ]);
    }
}
