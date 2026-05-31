<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = [
        'zone_name', 
        'additional_cost', 
        'is_active',
        'is_office',
        'address',
        'maps_link',
        'latitude',  
        'longitude', 
    ];
}