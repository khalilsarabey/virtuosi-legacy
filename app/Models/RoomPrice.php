<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class RoomPrice extends Model
{
    protected $guarded = ['id'];

    public function getStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('project.date_format')) : null;
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value ? Carbon::createFromFormat(config('project.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getEndDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('project.date_format')) : null;
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value ? Carbon::createFromFormat(config('project.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getStayDurationAttribute()
    {
        return $this->check_out->diffInDays($this->check_in);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeValidForRange($query, array $range = [])
    {
        return $query->where(function ($query) use ($range) {
            return $query
                // Covers outer bounds
                ->where(function ($query) use ($range) {
                    $query->where('start_date', '>=', reset($range))->where('end_date', '<=', end($range));
                })
                // Covers left and right bound
                ->orWhere(function ($query) use ($range) {
                    $query->whereBetween('start_date', $range)->orWhereBetween('end_date', $range);
                })
                // Covers inner bounds
                ->orWhere(function ($query) use ($range) {
                    $query->where('start_date', '<=', reset($range))
                        ->where('end_date', '>=', end($range));
                });
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
            $settings = Cache::rememberForever('settings', function() {
                return \App\Models\Agency::where('id', 1)->first();
            });
            $settingsArray = $settings->toArray();
            // Handle different possible return types
            if (isset($settingsArray['euro']) && is_numeric($settingsArray['euro'])) {
                $conversionRate = $settingsArray['euro'];
            } elseif (isset($settingsArray[0]['euro']) && is_numeric($settingsArray[0]['euro'])) {
                $conversionRate = $settingsArray[0]['euro'];
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
        return $value * $this->getConversionRate();
    }

    protected static function booted(): void
    {
        static::retrieved(function ($model) {
            if ($model->shouldConvertCurrency()) {
                foreach ($model->getConvertiblePriceFields() as $field) {
                    if (!is_null($model->{$field})) {
                        $model->{$field} = $model->convertPrice($model->{$field});
                    }
                }
            }
        });
    }

    public function shouldConvertCurrency(): bool
    {
        return !request()->is('v2/admin*');
    }

    public function getConvertiblePriceFields(): array
    {
        return [
            'adult_1', 'adult_2', 'adult_3', 'adult_4', 'adult_5', 'adult_6',
            'child_1', 'child_2', 'child_3', 'child_4', 'child_5', 'child_6',
            'infant', 'infant_1', 'infant_2', 'infant_3', 'infant_4', 'infant_5', 'infant_6',
        ];
    }
}
