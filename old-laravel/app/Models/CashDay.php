<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashDay extends Model
{
    protected $fillable = [
        'date',
        'opening_balance',
        'closing_balance',
        'opening_petty_balance',
        'closing_petty_balance',
        'opening_balance_usd',
        'closing_balance_usd',
        'opening_petty_balance_usd',
        'closing_petty_balance_usd',
        'status',
        'closed_by',
        'closed_at'
    ];

    protected $casts = [
        'date' => 'date',
        'closed_at' => 'datetime'
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function closer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
