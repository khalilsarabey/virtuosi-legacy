<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurkishCustomPricingDetail extends Model
{
    protected $guarded = ['id'];

    public function custom_pricing()
    {
        return $this->belongsTo(TurkishCustomPricing::class, 'turkish_custom_pricing_id', 'id');
    }
}
