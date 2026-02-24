<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RraFile extends Model
{
    protected $fillable = ['type', 'month', 'year', 'description', 'file_path', 'uploaded_by'];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }}
