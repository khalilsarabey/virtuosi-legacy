<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingAnnouncement extends Model
{
    protected $guarded = ['id'];

    public function getViewedByAttribute($details)
    {
        return json_decode($details);
    }

    public function setViewedByAttribute($value)
    {
        $this->attributes['viewed_by'] = json_encode($value);
    }
}
