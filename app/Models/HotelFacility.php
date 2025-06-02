<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelFacility extends Model
{
    protected $fillable = [
        'hotel_id', 'facility_name'
    ];
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
