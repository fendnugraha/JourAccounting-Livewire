<?php

namespace App\Livewire\Settings\Product;

use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedId;

    public function setProductId($id)
    {
        $this->selectedId = $id;
        $this->dispatch('product-selected', $id);
        $this->dispatch('open-modal', 'edit-product');
    }

    public function destroy(Product $product)
    {
        $transactionsExist = $product->transactions()->exists();
        if ($transactionsExist) {
            return;
        }

        $product->delete();
        $this->resetPage();

        $this->dispatch('productDeleted', 'Product deleted successfully');
    }

    #[On(['product-created', 'product-updated', 'product-deleted'])]
    public function render()
    {
        return view('livewire.settings.product.product-table', [
            'products' => Product::with('category:id,name')->where('name', 'like', '%' . $this->search . '%')->orderBy('name')->paginate(10)->onEachSide(0)
        ]);
    }
}
