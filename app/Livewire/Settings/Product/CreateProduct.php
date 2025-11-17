<?php

namespace App\Livewire\Settings\Product;

use App\Models\Product;
use App\Models\ProductCategory;
use Livewire\Component;

class CreateProduct extends Component
{
    public $name;
    public $category;
    public $price;
    public $cost;

    protected $rules = [
        'name' => 'required|unique:products,name',
        'category' => 'required',
        'price' => 'required|numeric',
        'cost' => 'required|numeric',
    ];

    public function createProduct()
    {
        $this->validate();
        $product = Product::create([
            'code' => Product::newCode($this->category),
            'name' => $this->name,
            'category' => $this->category,
            'price' => $this->price,
            'cost' => $this->cost,
        ]);

        $this->reset(['name', 'category', 'price', 'cost']);
        $this->dispatch('product-created', $product->id);

        session()->flash('success', 'Product created successfully.');
    }

    public function render()
    {
        return view('livewire.settings.product.create-product', [
            'categories' => ProductCategory::orderBy('name')->get()
        ]);
    }
}
