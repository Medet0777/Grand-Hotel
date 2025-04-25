<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id', 'hotel_id'
    ];

    // Связь "Многие к одному" с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь "Многие к одному" с отелем
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
