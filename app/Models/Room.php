<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['hotel_id','room_type','price_per_night','available'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function roomFacilities()
    {
        return $this->hasMany(Room_Facility::class);
    }
}
