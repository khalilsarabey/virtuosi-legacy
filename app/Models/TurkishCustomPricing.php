<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurkishCustomPricing extends Model
{
    use \Bkwld\Cloner\Cloneable;
    protected $cloneable_relations = ['classes'];
    protected $guarded = ['id'];
    protected $table = 'turkish_custom_pricings';

    public function classes()
    {
        return $this->hasMany(TurkishCustomPricingDetail::class, 'turkish_custom_pricing_id', 'id');
    }

    public function destination()
    {
        return $this->belongsTo(TurkishDestination::class, 'turkish_destination_id', 'id');
    }

    public function agency()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
