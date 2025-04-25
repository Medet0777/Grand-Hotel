<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel_Image extends Model
{
    protected $fillable = [
        'hotel_id', 'url'
    ];
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
