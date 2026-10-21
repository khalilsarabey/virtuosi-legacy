<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'tickets';

    public $orderable = [
        'id',
        'booking.reference',
        'flight.dep_date',
        'title',
        'first_name',
        'last_name',
        'date_of_birth',
        'phone',
        'nationality',
        'price',
    ];

    public $filterable = [
        'id',
        'booking.reference',
        'flight.dep_date',
        'title',
        'first_name',
        'last_name',
        'date_of_birth',
        'phone',
        'nationality',
        'price',
    ];

    protected $casts = [
        'date_of_birth' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = ['*'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function getDateOfBirthAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('project.date_format')) : null;
    }

    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['date_of_birth'] = $value ? Carbon::createFromFormat(config('project.date_format'), $value)->format('Y-m-d') : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
