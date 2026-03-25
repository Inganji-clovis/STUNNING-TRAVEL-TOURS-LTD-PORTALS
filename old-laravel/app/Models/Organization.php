<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'address'];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function billingDocuments()
    {
        return $this->hasMany(BillingDocument::class);
    }}
