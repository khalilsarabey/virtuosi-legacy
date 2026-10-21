<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property mixed $hotel_id
 */
class Room extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [
        'id'
    ];

    protected $appends = [
        'gallery'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function stopSales()
    {
        return $this->hasMany(StopSale::class, 'reference_id');
    }

    public function getStartDateAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format(config('project.date_format')) : null;
    }

    public function setStartDateAttribute($value): void
    {
        $this->attributes['start_date'] = $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getEndDateAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format(config('project.date_format')) : null;
    }

    public function setEndDateAttribute($value): void
    {
        $this->attributes['end_date'] = $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function room_type(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(RoomPrice::class);
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(RoomSpecification::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(BookingRoom::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'room_features');
    }

    public function getGalleryAttribute()
    {
        return $this->getMedia('room_gallery')->map(function ($item) {
            $media                      = $item->toArray();
            $media['url']               = config('app.image_url') . $item->getUrl();
            $media['preview']           = config('app.image_url') . $item->getUrl('preview');
            $media['large']             = config('app.image_url') . $item->getUrl('large');
            $media['medium']            = config('app.image_url') . $item->getUrl('medium');
            $media['small']             = config('app.image_url') . $item->getUrl('small');
            return $media;
        });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_MAX, 2200, null)->optimize();
        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_MAX, 1100, null)->optimize();
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_MAX, 460, null)->optimize();

        $this->addMediaConversion('preview')
            ->width(120)
            ->height(120)
            ->fit('crop', 120, 120);
    }
}
