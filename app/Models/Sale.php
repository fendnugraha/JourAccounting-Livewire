<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = ['id'];

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'invoice', 'invoice');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
