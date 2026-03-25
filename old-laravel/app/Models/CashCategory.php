<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashCategory extends Model
{
    protected $fillable = ['name', 'type', 'classification', 'is_active'];

    public function transactions(): HasMany
    {
        return $this->hasMany(CashTransaction::class, 'category_id');
    }
}
