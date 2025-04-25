<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room_Facility extends Model
{
    protected $fillable = ['room_id','facility'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
