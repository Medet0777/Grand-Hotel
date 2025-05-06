<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
     protected $fillable = ['name','location_id','rating','price_per_night','description'];



    public function rooms(): HasMany
     {
         return $this->hasmany(Room::class);
     }

    public function hotelImages(): HasMany
    {
        return $this->hasMany(Hotel_Image::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function userRatings(): HasMany
    {
        return $this->hasMany(UserRating::class);
    }

    public function hotelFacilities(): HasMany
    {
        return $this->hasMany(HotelFacility::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }



    public function calculateAverageRating(): float
    {
        $totalRating = 0;
        $reviewCount = $this->reviews()->count();

        if($reviewCount > 0)
        {
            $totalRating = $this->reviews()->sum('rating');
            return round($totalRating/ $reviewCount, 1);
        }

        return $this->rating;
    }

    public function updateAverageRating(): void
    {
        $this->rating = $this->calculateAverageRating();
        $this->save();
    }
}
