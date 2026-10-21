<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $guarded = ['id'];

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

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

}
