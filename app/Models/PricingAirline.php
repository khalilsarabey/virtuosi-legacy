<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricingAirline extends Model
{
    use HasAdvancedFilter;
    use SoftDeletes;

    protected $table = 'pricing_airlines';

    public $orderable = [
        'id',
        'name',
    ];

    public $filterable = [
        'id',
        'name'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(PricingAirline::class, 'parent_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(PricingCategory::class, 'pricing_category_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(PricingAirline::class, 'parent_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
