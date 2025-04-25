<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    protected $fillable = [
        'user_id', 'rating'
    ];

    // Связь "Многие к одному" с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
