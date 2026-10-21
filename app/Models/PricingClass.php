<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingClass extends Model
{
    protected $table = 'pricing_classes';

    public $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(PricingClassDetail::class);
    }

    public function airline()
    {
        return $this->belongsTo(PricingAirline::class, 'airline_id');
    }

    public function agencies()
    {
        return $this->hasMany(User::class);
    }
}
