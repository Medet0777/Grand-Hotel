<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationFacility extends Model
{
    protected $fillable = [
        'location_id', 'facility_name'
    ];
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
