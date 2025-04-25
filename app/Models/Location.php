<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name','latitude','longitude'];

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    public function locationFacilities()
    {
        return $this->hasMany(LocationFacility::class);
    }
}
