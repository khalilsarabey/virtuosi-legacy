<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingCategory extends Model
{
    protected $table = 'pricing_categories';
    protected $guarded = ['id'];

    public function airlines()
    {
        return $this->hasMany(PricingAirline::class);
    }
}
