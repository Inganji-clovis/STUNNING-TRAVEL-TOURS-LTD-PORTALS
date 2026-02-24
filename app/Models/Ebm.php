<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ebm extends Model
{
    protected $fillable = ['company_name', 'tin_number', 'description', 'date', 'amount_rwf', 'vat_amount', 'billing_document_id', 'file_path', 'uploaded_by'];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function invoice()
    {
        return $this->belongsTo(BillingDocument::class, 'billing_document_id');
    }
}
