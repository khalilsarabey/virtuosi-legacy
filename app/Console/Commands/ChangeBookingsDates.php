<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ChangeBookingsDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bookings = \App\Models\Booking::query()
            ->where('dep_date', '>=', now()->subDay())
            ->where(function($query) {
                $query->where('dep_airline_name', 'Pegasus Airlines')
                    ->orWhere('ret_airline_name', 'Pegasus Airlines');
            })
            ->with([
                'tickets',
                'depFlight',
                'retFlight'
            ])
            ->get();
        $bookings->each(function ($booking) {
                if($booking->depFlight?->airline_id == 731 || $booking->retFlight?->airline_id == 731) {
                    $bookingUpdate = [];
                    if($booking->depFlight) {
                        $bookingUpdate['dep_date'] = $booking->depFlight->dep_date;
                        $bookingUpdate['dep_time'] = $booking->depFlight->dep_time;
                        $bookingUpdate['dep_arr_time'] = $booking->depFlight->dep_arr_time;
                    }
                    if($booking->retFlight) {
                        $bookingUpdate['ret_date'] = $booking->retFlight->dep_date;
                        $bookingUpdate['ret_time'] = $booking->retFlight->dep_time;
                        $bookingUpdate['ret_arr_time'] = $booking->retFlight->dep_arr_time;
                    }
                    if(count($bookingUpdate)) {
                        $booking->update($bookingUpdate);
                        $booking->tickets->each(function ($ticket) {
                            $ticket->update([
                                'dep_date' => $ticket->flight->dep_date,
                                'dep_time' => $ticket->flight->dep_time,
                                'dep_arr_time' => $ticket->flight->dep_arr_time,
                            ]);
                        });
                    }
                }
        });
    }
}
