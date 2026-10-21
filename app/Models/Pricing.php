<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    public $table = 'pricings';

    public $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function airline()
    {
        return $this->belongsTo(PricingAirline::class, 'pricing_airline_id');
    }
}
