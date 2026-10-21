<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'bookings';

    public $orderable = [
        'id',
        'user.name',
        'user.email',
        'reference',
        'status',
        'type',
        'flight.dep_date',
        'dep_flight.dep_date',
        'ret_flight.dep_date',
        'infants',
        'adults',
        'children',
        'price',
        'agency_id',
        'created_at',
    ];

    public $filterable = [
        'id',
        'user.name',
        'user.email',
        'agency_id',
        'reference',
        'status',
        'type',
        'flight.dep_date',
        'dep_flight.dep_date',
        'ret_flight.dep_date',
        'infants',
        'adults',
        'children',
        'price',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $guarded = ['id'];

    public function rooms()
    {
        return $this->hasMany(BookingRoom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function depFlight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function retFlight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.date_format')) : null;
    }

    public function getCheckInAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getCheckOutAttribute($value)
    {
        return Carbon::parse($value)->addDay();
    }

    public function setCheckOutAttribute($value)
    {
        $this->attributes['check_out'] = Carbon::parse($value)->subDay();
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function getNextId()
    {
        $statement = DB::select("show table status like 'bookings'");
        return $statement[0]->Auto_increment;
    }

    public function getReferenceAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y') .  str_pad($this->id, 4, "0", STR_PAD_LEFT);
    }

    public static function boot() {
        parent::boot();
        static::creating(function ($booking) {
            $booking->agency_id = auth()->user()->agency_id;
        });
        static::created(function ($booking) {
            $booking->updateQuietly([
                'reference' => date('Y') . str_pad($booking->id, 4, "0", STR_PAD_LEFT),
            ]);
        });
    }
}
