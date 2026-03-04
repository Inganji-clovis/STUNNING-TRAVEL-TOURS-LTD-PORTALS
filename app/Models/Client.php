<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'tin',
        'organization_id',
        'registered_by',
        'membership_type',
        'membership_number',
        'airline_name',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function travelDocuments()
    {
        return $this->hasMany(TravelDocument::class);
    }

    public function billingDocuments()
    {
        return $this->hasMany(BillingDocument::class);
    }

    public function registrar()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }}
