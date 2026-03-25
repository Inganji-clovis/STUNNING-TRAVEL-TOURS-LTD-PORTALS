<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPayment extends Model
{
    protected $fillable = [
        'client_id', 'billing_document_id', 'total_due', 'paid_amount', 'remaining_amount',
        'payment_date', 'payment_method', 'currency', 'receipt_file', 'recorded_by'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function invoice()
    {
        return $this->belongsTo(BillingDocument::class, 'billing_document_id');
    }
}
