<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'locations';

    public $orderable = [
        'id',
        'airport',
        'airport_code',
        'city',
        'country',
        'active',
    ];

    public $filterable = [
        'id',
        'airport',
        'airport_code',
        'city',
        'country',
        'active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'airport',
        'airport_code',
        'city',
        'city_code',
        'country_code',
        'country',
        'timezone',
        'lat',
        'lon',
        'active',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getFullAttribute()
    {
        return $this->city . ' - ' . $this->airport;
    }
}
