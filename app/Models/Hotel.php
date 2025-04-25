<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
     protected $fillable = ['name','location','rating','price_per_night','description'];

     public function rooms()
     {
         return $this->hasmany(Room::class);
     }

    public function hotelImages()
    {
        return $this->hasMany(Hotel_Image::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function userRatings()
    {
        return $this->hasMany(UserRating::class);
    }

    public function hotelFacilities()
    {
        return $this->hasMany(HotelFacility::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
