<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{

    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(Feature::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Feature::class, 'parent_id');
    }

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_features');
    }
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_features');
    }
}
