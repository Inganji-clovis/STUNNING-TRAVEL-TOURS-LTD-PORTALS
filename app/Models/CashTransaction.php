<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashTransaction extends Model
{
    protected $fillable = [
        'cash_day_id',
        'category_id',
        'type',
        'amount',
        'currency',
        'source',
        'description',
        'created_by',
        'tts',
        'service_fee',
        'supplier_name',
        'payment_method'
    ];

    public function cashDay(): BelongsTo
    {
        return $this->belongsTo(CashDay::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CashCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
