<?php

namespace App\Livewire\Setting\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;

class EditProduct extends Component
{
    public $product_id;
    public $name;
    public $cost;
    public $price;

    public function mount($product_id)
    {
        $product = Product::find($product_id);
        $this->product_id = $product->id;
        $this->name = $product->name;
        $this->cost = $product->cost;
        $this->price = $product->price;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|max:255|min:5|unique:products,name,' . $this->product_id,
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $product = Product::find($this->product_id);
        $product->update([
            'name' => $this->name,
            'cost' => $this->cost,
            'price' => $this->price,
        ]);

        return redirect()->route('product')->with('success', 'Product updated successfully');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $product = Product::find($this->product_id);

        return view('livewire.setting.product.edit-product', [
            'title' => 'Edit Product',
            'product' => $product
        ]);
    }
}
