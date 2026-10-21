<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelContract extends Model
{
    protected $guarded = ['id'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

}
