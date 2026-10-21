<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StopSale extends Model
{

    protected $table = 'stop_sales';
    protected $guarded = ['id'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'reference_id', 'id');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'reference_id', 'id');
    }

}
