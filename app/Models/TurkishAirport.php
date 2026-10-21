<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurkishAirport extends Model
{
    use \Bkwld\Cloner\Cloneable;
    protected $guarded = ['id'];
    public function destination()
    {
        return $this->belongsTo(TurkishDestination::class, 'turkish_destination_id', 'id');
    }
}
