<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Cache;

class Hotel extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [
        'id'
    ];

    protected $appends = [
        'gallery',
        'logo',
    ];


    protected $casts = [
        'agencies_permissions' => 'array'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'location', 'city_code');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'hotel_features');
    }

    public function specifications(): HasMany
    {
        return $this->hasMany('HotelSpecification');
    }

    public function getGalleryAttribute()
    {
        return $this->getMedia('hotel_gallery')->map(function ($item) {
            $media                      = $item->toArray();
            $media['url']               = config('app.image_url') . $item->getUrl();
            $media['preview']           = config('app.image_url') . $item->getUrl('preview');
            $media['large']             = config('app.image_url') . $item->getUrl('large');
            $media['medium']            = config('app.image_url') . $item->getUrl('medium');
            $media['small']             = config('app.image_url') . $item->getUrl('small');
            return $media;
        });
    }

    public function getLogoAttribute()
    {
        return $this->getMedia('hotel_logo')->map(function ($item) {
            $media                      = $item->toArray();
            $media['url']               = config('app.image_url') . $item->getUrl();
            $media['preview']           = config('app.image_url') . $item->getUrl('preview');
            $media['large']             = config('app.image_url') . $item->getUrl('large');
            $media['medium']            = config('app.image_url') . $item->getUrl('medium');
            $media['small']             = config('app.image_url') . $item->getUrl('small');
            return $media;
        });
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('large')
            ->fit(Fit::Max, 2200, null)->optimize();
        $this->addMediaConversion('medium')
            ->fit(Fit::Max, 1100, null)->optimize();
        $this->addMediaConversion('small')
            ->fit(Fit::Max, 460, null)->optimize();

        $this->addMediaConversion('preview')
            ->width(120)
            ->height(120)
            ->fit(Fit::Crop, 120, 120);
    }

    /**
     * Boot the model.
     *
     * This method sets up event listeners for the model.
     */
    protected static function boot()
    {
        parent::boot();

        // When a hotel is created or updated
        static::saved(function ($hotel) {
            // Check if the currency field exists and has been changed
            if ($hotel->exists && $hotel->currency) {
                // Update all rooms that belong to this hotel
                $hotel->rooms()->update(['currency' => $hotel->currency]);

                // Update all room prices that belong to rooms of this hotel
                foreach ($hotel->rooms as $room) {
                    $room->prices()->update(['currency' => $hotel->currency]);
                }
            }
        });
    }

    /**
     * Get the conversion rate for the hotel's currency
     *
     * @return float
     */
    public function getConversionRate()
    {
        // Default to 1 (no conversion)
        $conversionRate = 1;

        if (strtoupper($this->currency) == 'EURO') {
            try {
                $settings = Cache::rememberForever('settings', function() {
                    return \App\Models\Agency::where('id', 1)->first();
                });

                // Handle different possible return types
                if (is_object($settings)) {
                    // Case 1: $settings is a model instance
                    if (isset($settings->euro) && is_numeric($settings->euro)) {
                        $conversionRate = (float)$settings->euro;
                    }
                } elseif (is_array($settings) || $settings instanceof \Illuminate\Support\Collection) {
                    // Case 2: $settings is an array or collection
                    if (isset($settings[0]) && isset($settings[0]->euro) && is_numeric($settings[0]->euro)) {
                        $conversionRate = (float)$settings[0]->euro;
                    }
                }
            } catch (\Exception $e) {
                // Log the error but continue with default conversion rate
                \Illuminate\Support\Facades\Log::error('Error getting conversion rate: ' . $e->getMessage());
            }
        }

        return $conversionRate;
    }

    /**
     * Convert a price field based on the hotel's currency
     *
     * @param mixed $value
     * @return mixed
     */
    protected function convertPrice($value)
    {
        if(request()->is('v2/admin*')) {
            return $value;
        } else if (is_numeric($value)) {
            return $value * $this->getConversionRate();
        } else {
            return $value;
        }
    }

    /**
     * Get the BB attribute with currency conversion
     *
     * @param mixed $value
     * @return mixed
     */
    public function getBbAttribute($value)
    {
        return $this->convertPrice($value);
    }

    /**
     * Get the FB attribute with currency conversion
     *
     * @param mixed $value
     * @return mixed
     */
    public function getFbAttribute($value)
    {
        return $this->convertPrice($value);
    }

    /**
     * Get the AI attribute with currency conversion
     *
     * @param mixed $value
     * @return mixed
     */
    public function getAiAttribute($value)
    {
        return $this->convertPrice($value);
    }

    /**
     * Get the SAI attribute with currency conversion
     *
     * @param mixed $value
     * @return mixed
     */
    public function getSaiAttribute($value)
    {
        return $this->convertPrice($value);
    }

    /**
     * Get the UAI attribute with currency conversion
     *
     * @param mixed $value
     * @return mixed
     */
    public function getUaiAttribute($value)
    {
        return $this->convertPrice($value);
    }

    /**
     * Get the HB attribute with currency conversion
     *
     * @param mixed $value
     * @return mixed
     */
    public function getHbAttribute($value)
    {
        return $this->convertPrice($value);
    }

    public function scopeRestrict($query, $seats = null)
    {
        // Filter by agency permissions
        $query->where(function ($q) {
            $q
                ->where('agencies_permissions', 'NOT LIKE', '%' . auth()->user()->email . '%')
                ->orWhere('agencies_permissions', '')
                ->orWhereNull('agencies_permissions');
        });
        return $query;
    }

}
