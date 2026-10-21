<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Update extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'updates';

    public $orderable = [
        'id',
        'text',
    ];

    public $filterable = [
        'id',
        'text',
    ];

    protected $fillable = [
        'text',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($update) {
            $update->agency_id = auth()->user()->agency_id;
        });
        static::updating(function ($update) {
            $update->agency_id = auth()->user()->agency_id;
        });
    }
}
