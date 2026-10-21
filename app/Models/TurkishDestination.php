<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurkishDestination extends Model
{
    use \Bkwld\Cloner\Cloneable;
    protected $cloneable_relations = ['classes', 'airports', 'pricing'];
    protected $guarded = ['id'];
    protected $table = 'turkish_destinations';
    public function classes()
    {
        return $this->hasMany(TurkishDestinationDetail::class, 'turkish_destination_id', 'id');
    }

    public function airports()
    {
        return $this->hasMany(TurkishAirport::class, 'turkish_destination_id', 'id');
    }

    public function pricing()
    {
        return $this->hasMany(TurkishCustomPricing::class, 'turkish_destination_id', 'id');
    }
}
