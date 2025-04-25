<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelFacility extends Model
{
    protected $fillable = [
        'hotel_id', 'facility_name'
    ];


    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
