<?php

namespace App\Observers;

use App\Models\Flight;
use App\Models\User;
use App\Notifications\DataChangeEmailNotification;
use Notification;

class FlightObserver
{

    public function created(Flight $flight): void
    {
        $payload = [
            'action' => 'created',
            'model'  => sprintf('%s#%s', get_class($flight), $flight->id),
            'reason' => auth()->user(),
        ];

        $admins = User::admins()->get();

        Notification::send($admins, new DataChangeEmailNotification($payload));
    }

    public function updated(Flight $flight): void
    {
        $payload = [
            'action' => 'updated',
            'model'  => sprintf('%s#%s', get_class($flight), $flight->id),
            'reason' => auth()->user(),
        ];

        $admins = User::admins()->get();

        Notification::send($admins, new DataChangeEmailNotification($payload));
    }

    public function deleted(Flight $flight): void
    {
        $payload = [
            'action' => 'deleted',
            'model'  => sprintf('%s#%s', get_class($flight), $flight->id),
            'reason' => auth()->user(),
        ];

        $admins = User::admins()->get();

        Notification::send($admins, new DataChangeEmailNotification($payload));
    }
}
