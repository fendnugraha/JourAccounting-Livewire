<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = ['id'];

    public function receivables()
    {
        return $this->hasMany(Receivable::class, 'contact_id');
    }

    public function payables()
    {
        return $this->hasMany(Payable::class, 'contact_id');
    }
}
