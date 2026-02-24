<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'file_path',
        'pnr',
        'ticket_number',
        'client_names',
        'departure_date',
        'return_date',
        'flight_number',
        'airline_name',
        'created_by'
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
