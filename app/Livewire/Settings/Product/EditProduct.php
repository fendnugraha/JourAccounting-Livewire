<?php

namespace App\Livewire\Settings\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;

class EditProduct extends Component
{
    public $product;

    public $name;
    public $price;
    public $cost;

    #[On(['product-selected'])]
    public function loadProduct($id)
    {
        $this->product = $id;

        $product = Product::find($id);
        $this->name = $product->name;
        $this->price = $product->price;
        $this->cost = $product->cost;
    }

    public function editProduct()
    {
        $product = Product::find($this->product);
        $product->name = $this->name;
        $product->price = $this->price;
        $product->cost = $this->cost;
        $product->save();
        $this->dispatch('product-updated', $product->id);
    }

    public function render()
    {
        return view('livewire.settings.product.edit-product');
    }
}
