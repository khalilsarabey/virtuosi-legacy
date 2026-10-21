<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasFactory;
    use HasAdvancedFilter;

    public $table = 'prices';

    public $orderable = [
        'id',
        'flight.dep_date',
        'flight.dep_time',
        'flight.arr_time',
        'adult',
        'child',
        'infant',
        'seats',
    ];

    public $filterable = [
        'id',
        'flight.dep_date',
        'flight.dep_time',
        'flight.arr_time',
        'adult',
        'child',
        'infant',
        'seats',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'flight_id',
        'adult',
        'child',
        'infant',
        'package_adult',
        'package_child',
        'package_infant',
        'seats',
        'class',
        'position',
        'type'
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
