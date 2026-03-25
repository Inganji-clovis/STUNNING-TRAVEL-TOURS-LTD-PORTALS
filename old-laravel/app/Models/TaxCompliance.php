<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxCompliance extends Model
{
    protected $fillable = [
        'type', 'declaration_number', 'declared_amount', 'declaration_file',
        'receipt_amount', 'payment_date', 'receipt_file', 'uploaded_by'
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }}
