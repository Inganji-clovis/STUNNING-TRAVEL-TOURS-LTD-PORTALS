<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelDocument extends Model
{
    protected $fillable = [
        'client_id', 'type', 'passport_number', 'country_code', 
        'nationality', 'dob', 'place_of_birth', 'gender', 
        'file_path', 'expiry_date'
    ];

    protected $casts = [
        'dob' => 'date',
        'expiry_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }}
