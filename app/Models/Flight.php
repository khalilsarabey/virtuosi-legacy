<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Flight extends Model
{
    use HasAdvancedFilter;
    use SoftDeletes;

    public const TYPE_SELECT = [
        'oneway' => 'One Way',
        'fixed' => 'Fixed',
    ];

    public const ONHOLD_BOOKINGS = [
        '1' => 'Yes',
        '0' => 'No',
    ];

    public $table = 'flights';

    protected $casts = [
        'agencies_permissions' => 'array',
        'whitelabel_permissions' => 'array'
    ];

    public $orderable = [
        'id',
        'dep_from.code',
        'dep_from.airport',
        'dep_from.city',
        'dep_to.code',
        'dep_to.airport',
        'dep_to.city',
        'dep_date',
        'dep_time',
        'arr_time',
        'ret_from.code',
        'ret_from.airport',
        'ret_from.city',
        'ret_to.code',
        'ret_to.airport',
        'ret_to.city',
        'ret_dep_date',
        'ret_dep_time',
        'ret_arr_time',
        'baggage_allowance',
        'contract.name',
        'active',
        'type',
        'oneway_supplements',
        'multi_supplements',
        'seats',
    ];

    public $filterable = [
        'id',
        'category.name',
        'dep_from.code',
        'dep_from.airport',
        'dep_from.city',
        'dep_to.code',
        'dep_to.airport',
        'dep_to.city',
        'dep_date',
        'dep_time',
        'arr_time',
        'ret_from.code',
        'ret_from.airport',
        'ret_from.city',
        'ret_to.code',
        'ret_to.airport',
        'ret_to.city',
        'ret_dep_date',
        'ret_dep_time',
        'ret_arr_time',
        'baggage_allowance',
        'contract.name',
        'active',
        'type',
        'oneway_supplements',
        'multi_supplements',
        'seats',
    ];

    protected $dates = [
        'dep_date',
        'ret_dep_date',
        'expiry_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'permission',
        'package_note',
        'package_supplements',
        'business_seats',
        'stop_booking_before',
        'remaining_seats_display',
        'dep_from_code',
        'dep_to_code',
        'dep_date',
        'dep_time',
        'arr_time',
        'ret_from_code',
        'ret_to_code',
        'ret_dep_date',
        'ret_dep_time',
        'ret_arr_time',
        'baggage_allowance',
        'contract_id',
        'active',
        'expiry_date',
        'type',
        'onhold_bookings',
        'oneway_supplements',
        'multi_supplements',
        'combinable_supplements',
        'agencies_permissions',
        'whitelabel_permissions',
        'seats'
    ];

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function category()
    {
        return $this->belongsToMany(Category::class);
    }

    public function depFrom()
    {
        return $this->belongsTo(Location::class, 'dep_from_code', 'airport_code');
    }

    public function depTo()
    {
        return $this->belongsTo(Location::class, 'dep_to_code', 'airport_code');
    }

    public function retFrom()
    {
        return $this->belongsTo(Location::class, 'ret_from_code', 'airport_code');
    }

    public function retTo()
    {
        return $this->belongsTo(Location::class, 'ret_to_code', 'airport_code');
    }

    public function getDepartureDurationAttribute()
    {
        $a = \Carbon\Carbon::parse($this->dep_time);
        $d = \Carbon\Carbon::parse($this->dep_arr_time);
        return $a->diff($d)->format('%h.%I');
    }

    public function getReturnDurationAttribute()
    {
        $a = \Carbon\Carbon::parse($this->ret_dep_time);
        $d = \Carbon\Carbon::parse($this->ret_arr_time);
        return $a->diff($d)->format('%h.%I');
    }

    public function setDepartureArrivalDate()
    {
        $date = \Carbon\Carbon::parse($this->dep_date);
        $a = \Carbon\Carbon::parse($this->dep_time);
        $d = \Carbon\Carbon::parse($this->dep_arr_time);
        //$date->minute(35);
        return $date;
    }

    public function getDepDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('project.date_format')) : null;
    }

    public function setDepDateAttribute($value)
    {
        $this->attributes['dep_date'] = $value ? Carbon::createFromFormat(config('project.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getExpiryDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('project.date_format')) : null;
    }

    public function setExpiryDateAttribute($value)
    {
        $this->attributes['expiry_date'] = $value ? Carbon::createFromFormat(config('project.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getRetDepDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('project.date_format')) : null;
    }

    public function setRetDepDateAttribute($value)
    {
        $this->attributes['ret_dep_date'] = $value ? Carbon::createFromFormat(config('project.date_format'), $value)->format('Y-m-d') : null;
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    public function departure_from_airport()
    {
        return $this->belongsTo(Location::class, 'dep_from_code', 'airport_code');
    }

    public function departure_to_airport()
    {
        return $this->belongsTo(Location::class, 'dep_to_code', 'airport_code');
    }

    public function return_from_airport()
    {
        return $this->belongsTo(Location::class, 'ret_from_code', 'airport_code');
    }

    public function return_to_airport()
    {
        return $this->belongsTo(Location::class, 'ret_to_code', 'airport_code');
    }

    public function getTypeLabelAttribute($value)
    {
        return static::TYPE_SELECT[$this->type] ?? null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getProductPriceAttribute()
    {
        return $this->prices()->sum('seats');
    }

    public function scopeRestrict($query, $seats = null)
    {
        $query
            ->withCount(['tickets as tickets_count' => function ($q) {
                $q->where('title', '<>', 'Infant.')->where('cabin', 'economy');
            }])
            ->withCount(['tickets as business_tickets_count' => function ($q) {
                $q->where('title', '<>', 'Infant.')->where('cabin', 'business');
            }])
            ->withSum(['prices as economy_seats' => function ($q) {
                $q->where('type', 'economy');
            }], 'seats')
            ->withSum(['prices as business_seats' => function ($q) {
                $q->where('type', 'business');
            }], 'seats');

        // Ensure the flight is active
        $query->where('active', 1);

        // Apply seat availability logic
        if ($seats) {
            // Only return flights with enough seats in either cabin
            $query->havingRaw('(COALESCE(economy_seats, 0) - tickets_count) >= ?', [$seats])
                  ->orHavingRaw('(COALESCE(business_seats, 0) - business_tickets_count) >= ?', [$seats]);
        } else {
            // Return flights with at least one available seat in either cabin
            $query->havingRaw('COALESCE(economy_seats, 0) > tickets_count')
                  ->orHavingRaw('COALESCE(business_seats, 0) > business_tickets_count');
        }

        // Filter by booking window and expiry date
        // Consider optimizing date checks as previously discussed
        $query->whereRaw("DATE(dep_date) >= DATE(NOW()) + INTERVAL stop_booking_before DAY");

        $query->where(function ($query) {
            $query->where('expiry_date', '>=', \Carbon\Carbon::now()->startOfDay()->toDateString())
                ->orWhereNull('expiry_date');
        });

        // Filter by agency permissions
        $query->where(function ($q) {
            $q
                ->where('agencies_permissions', 'NOT LIKE', '%' . auth()->user()->email . '%')
                ->orWhere('agencies_permissions', '')
                ->orWhereNull('agencies_permissions');
        });

        // NOTE: Removed explicit groupBy, Eloquent often handles this with withCount/Sum + having.
        // If grouping errors occur, add back: ->groupBy('flights.id') (replace flights.id if needed)

        return $query; // Return the modified query builder instance
    }

    public function scopeSearching($query)
    {
        return $query->where('flights.active', 1)
            ->whereRaw("DATE(dep_date) >= DATE(NOW()) + INTERVAL stop_booking_before DAY")
            ->join('locations as dep_from', 'flights.dep_from_code', '=', 'dep_from.airport_code')
            ->join('locations as dep_to', 'flights.dep_to_code', '=', 'dep_to.airport_code')
            ->select([
                'flights.type',
                'flights.dep_from_code',
                'dep_from.city as dep_from_city',
                'dep_from.city_code as dep_from_city_code',
                'flights.dep_to_code',
                'dep_to.city as dep_to_city',
                'dep_to.city_code as dep_to_city_code',
                'flights.dep_date',
                'flights.ret_dep_date',
                'flights.stop_booking_before'
            ])
            ->withCount(['tickets as tickets_count' => function ($q) {
                $q->where('title', '<>', 'Infant.');
            }])
            ->withSum('prices as all_seats', 'seats')
            ->havingRaw("all_seats > tickets_count")
            ->orderBy('dep_date');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($flight) {
            $flight->permissions()->delete();
        });
        static::creating(function ($flight) {
            $flight->agency_id = auth()->user()->agency_id;
        });
    }

}
