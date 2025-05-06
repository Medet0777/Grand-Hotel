<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = ['name','latitude','longitude'];

    public function hotels(): HasMany
    {
        return $this->hasMany(Hotel::class);
    }

    public function locationFacilities()
    {
        return $this->hasMany(LocationFacility::class);
    }
}
