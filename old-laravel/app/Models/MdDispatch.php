<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MdDispatch extends Model
{
    protected $fillable = [
        'sender_id',
        'type',
        'file_path',
        'description',
        'file_name',
        'status'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
