<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\DataChangeEmailNotification;
use Notification;

class BookingObserver
{
    public function created(Booking $booking): void
    {
        $payload = [
            'action' => 'created',
            'model'  => sprintf('%s#%s', get_class($booking), $booking->id),
            'reason' => auth()->user(),
        ];

        $admins = User::admins()->get();

        Notification::send($admins, new DataChangeEmailNotification($payload));
    }

    public function updated(Booking $booking): void
    {
        $payload = [
            'action' => 'updated',
            'model'  => sprintf('%s#%s', get_class($booking), $booking->id),
            'reason' => auth()->user(),
        ];

        $admins = User::admins()->get();

        Notification::send($admins, new DataChangeEmailNotification($payload));
    }
}
