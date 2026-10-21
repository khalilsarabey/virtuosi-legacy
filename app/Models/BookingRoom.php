<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingRoom extends Model
{
    use SoftDeletes;

    protected $table = 'booking_rooms';
    protected $guarded = ['id'];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCheckInAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format(config('project.date_format')) : null;
    }

    public function getCheckOutAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->addDay()->format(config('project.date_format')) : null;
    }

    public function setCheckOutAttribute($value): void
    {
        $this->attributes['check_out'] = $value ? Carbon::parse($value)->subDay()->format('Y-m-d') : null;
    }
}
