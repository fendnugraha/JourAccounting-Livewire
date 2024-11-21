<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sale()
    {
        return $this->hasMany(Transaction::class);
    }

    public function newCode($category)
    {
        $lastCode = $this->select(DB::raw('MAX(RIGHT(code,4)) AS lastCode'))
            ->where('category', $category)
            ->get();

        $lastCode = $lastCode[0]->lastCode;
        if ($lastCode != null) {
            $kd = $lastCode + 1;
        } else {
            $kd = "0001";
        }

        $category_slug = Category::where('name', $category)->first();

        return $category_slug->slug . '' . \sprintf("%04s", $kd);
    }

    public static function udpateCostAndStock($id, $newQty, $newStock, $newCost, $warehouse_id)
    {
        $product = Product::find($id);

        $initial_stock = $product->end_stock;
        $initial_cost = $product->cost;
        $initTotal = $initial_stock * $initial_cost;

        $newTotal = $newStock * $newCost;

        $updatedCost = ($initTotal + $newTotal) / ($initial_stock + $newStock);

        $product_log = Transaction::where('product_id', $product->id)->sum('quantity');
        $end_Stock = $product->stock + $product_log;
        Product::where('id', $product->id)->update([
            'end_Stock' => $end_Stock,
            'cost' => $updatedCost,
        ]);

        $updateWarehouseStock = WarehouseStock::where('warehouse_id', $warehouse_id)->where('product_id', $product->id)->first();
        if ($updateWarehouseStock) {
            $updateWarehouseStock->current_stock += $newQty;
            $updateWarehouseStock->save();
        } else {
            $warehouseStock = new WarehouseStock();
            $warehouseStock->warehouse_id = $warehouse_id;
            $warehouseStock->product_id = $product->id;
            $warehouseStock->init_stock = 0;
            $warehouseStock->current_stock = $newQty;
            $warehouseStock->save();
        }

        return $data = [
            'updatedCost' => $updatedCost,
            'end_Stock' => $end_Stock
        ];
    }
}
