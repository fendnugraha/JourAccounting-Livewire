<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    protected $guarded = ['id'];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'invoice', 'invoice');
    }

    public static function getLastPayment($invoice)
    {
        return self::where('invoice', $invoice)->max('payment_nth');
    }
}
