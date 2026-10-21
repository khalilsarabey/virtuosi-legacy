<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingDestination extends Model
{
    protected $table = 'pricing_destinations';
    protected $guarded = ['*'];

    public function category()
    {
        return $this->belongsTo(PricingCategory::class);
    }

}
