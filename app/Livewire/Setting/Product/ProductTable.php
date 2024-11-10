<?php

namespace App\Livewire\Setting\Product;

use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class ProductTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $name;
    public $slug;

    public function delete($id)
    {
        $product = Product::find($id);

        $salesExists = Sale::where('product_id', $id)->exists();
        if ($salesExists) {
            session()->flash('error', 'Product Cannot be Deleted!');
        } else {
            $product->delete();
            session()->flash('success', 'Product Deleted Successfully');
        }

        $this->dispatch('ProductDeleted');
    }

    public function addCategory()
    {
        $validate = $this->validate([
            'slug' => 'required|unique:categories,slug|size:3',
            'name' => 'required|unique:categories,name',
        ]);

        Category::create($validate);

        $this->dispatch('ProductCreated', 'Category Added Successfully');

        session()->flash('success', 'Category Added Successfully');

        $this->reset();
    }

    #[On('ProductCreated', 'ProductDeleted')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.setting.product.product-table', [
            'title' => 'Product',
            'products' => Product::where('name', 'like', '%' . $this->search . '%')->paginate($this->perPage),
        ]);
    }
}
