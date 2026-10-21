<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Airline extends Model
{
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'airlines';

    public $orderable = [
        'id',
        'name',
        'iata',
        'icao',
        'sign',
        'country',
    ];

    public $filterable = [
        'id',
        'name',
        'iata',
        'icao',
        'sign',
        'country',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'iata',
        'icao',
        'sign',
        'thumbnail',
        'country',
        'thumbnail',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
