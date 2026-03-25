<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'billing_document_id',
        'passenger_name',
        'airline',
        'destination',
        'price_rwf',
        'price_usd',
    ];

    public function invoice()
    {
        return $this->belongsTo(BillingDocument::class, 'billing_document_id');
    }
}
