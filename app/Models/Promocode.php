<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    protected $fillable = ['promocode','discount_percentage','valid_until'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
