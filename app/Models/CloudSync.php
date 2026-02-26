<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CloudSync extends Model
{
    protected $fillable = [
        'year',
        'month',
        'status',
        'synced_by',
        'error_log',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'synced_by');
    }
}
