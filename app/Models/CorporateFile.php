<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateFile extends Model
{
    protected $fillable = [
        'folder_id', 'name', 'file_path', 'file_type', 
        'source_document_id', 'source_document_type', 'uploaded_by'
    ];

    public function folder()
    {
        return $this->belongsTo(CorporateFolder::class, 'folder_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
