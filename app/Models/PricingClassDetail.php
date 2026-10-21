<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingClassDetail extends Model
{
    protected $guarded = ['id'];

    protected $table = 'pricing_class_details';

    public function pricingClass()
    {
        return $this->belongsTo(PricingClass::class);
    }

    public function airline()
    {
        return $this->belongsTo(PricingAirline::class, 'pricing_airline_id');
    }

}
