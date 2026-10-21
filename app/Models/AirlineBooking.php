<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirlineBooking extends Model
{

    public $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function integration()
    {
        return $this->belongsTo(Integration::class, 'integration_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function departure_duration()
    {
        $dep = $this->dep_from_date;
        $arr = $this->dep_to_date;
        $durationInMinutes = \Carbon\Carbon::parse($dep)->diffInMinutes(\Carbon\Carbon::parse($arr));
        if ($durationInMinutes < 60) {
            // If less than an hour, display "in xx minutes"
            $durationText = "{$durationInMinutes} minutes";
        } else {
            // If greater than an hour, display "in xx hour and xx minutes"
            $hours = floor($durationInMinutes / 60);
            $minutes = $durationInMinutes % 60;

            $durationText = "{$hours} hour";
            if ($minutes > 0) {
                $durationText .= " and {$minutes} minutes";
            }
        }
        return $durationText;
    }

    public function return_duration()
    {
        $dep = $this->ret_from_date;
        $arr = $this->ret_to_date;
        $durationInMinutes = \Carbon\Carbon::parse($dep)->diffInMinutes(\Carbon\Carbon::parse($arr));
        if ($durationInMinutes < 60) {
            // If less than an hour, display "in xx minutes"
            $durationText = "{$durationInMinutes} minutes";
        } else {
            // If greater than an hour, display "in xx hour and xx minutes"
            $hours = floor($durationInMinutes / 60);
            $minutes = $durationInMinutes % 60;

            $durationText = "{$hours} hour";
            if ($minutes > 0) {
                $durationText .= " and {$minutes} minutes";
            }
        }
        return $durationText;
    }
}
