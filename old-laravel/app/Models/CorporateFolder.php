<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateFolder extends Model
{
    protected $fillable = ['name', 'type', 'client_id', 'organization_id', 'year', 'created_by'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function files()
    {
        return $this->hasMany(CorporateFile::class, 'folder_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
