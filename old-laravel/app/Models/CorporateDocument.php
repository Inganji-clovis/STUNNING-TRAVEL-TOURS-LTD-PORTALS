<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateDocument extends Model
{
    protected $fillable = ['title', 'category', 'description', 'file_path', 'uploaded_by'];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }}
