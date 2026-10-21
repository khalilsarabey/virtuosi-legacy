<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelRule extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'sub_rules_json' => 'array',
        'hotel_ids' => 'array',
    ];

    public function getSubRulesAttribute()
    {
        return $this->sub_rules_json ?: [];
    }
}
