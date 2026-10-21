<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Illuminate\Support\Facades\Storage;
use Log;

class MigrateFlightsAndBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'samad:migrate';

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
        $this->info('Fetching extra flights...');

        $extras = \App\Models\Flight::query();
        $extras->where('airline_id', 1104);
        $extras->where('dep_date', '>=', '2024-06-10');
        $extras->where('dep_from_code', '!=', 'BJV');
        $extras->where('dep_to_code', '!=', 'BJV');
        $extras->where('type', 'oneway');
        $extraFlights = $extras->get();
        $this->info('Extra flights fetched successfully! ' . $extraFlights->count());

        $matrix = [];

        DB::beginTransaction();
        try {
            foreach ($extraFlights as $flight) {
                $main = \App\Models\Flight::query();
                $main->where('airline_id', 731);
                $main->where('type', $flight->type);
                $main->where('dep_from_code', $flight->dep_from_code);
                $main->where('dep_to_code', $flight->dep_to_code);
                $main->whereDate('dep_date', Carbon::parse($flight->dep_date));
                $main->whereTime('dep_time', Carbon::parse($flight->dep_time));
                $main->whereTime('dep_arr_time', Carbon::parse($flight->dep_arr_time));
                $result = $main->first();
                if($result) {
                    $matrix[$flight->id] = $result->id;
                    $result->seats = $result->seats + $flight->seats;
                    $result->save();
                }
            }

            $this->info('Matrix has ' . count($matrix));

            $firstBookings = \App\Models\Booking::query();
            $firstBookings->whereIn('flight_id', array_keys($matrix));
            $firstBookings->whereIn('dep_flight_id', array_keys($matrix));
            $bookingsResult = $firstBookings->get();

            foreach($bookingsResult as $booking) {
                $booking->flight_id     = $matrix[$booking->flight_id];
                $booking->dep_flight_id = $matrix[$booking->dep_flight_id];
                $booking->save();
            }

            $bookings = \App\Models\Booking::query();
            $bookings->whereIn('ret_flight_id', array_keys($matrix));
            $secondBookingsResult = $bookings->get();

            $this->info('Found Second Batch Booking ' . count($secondBookingsResult) . ' Matrix: ' . count($matrix));

            foreach($secondBookingsResult as $b) {
                $b->ret_flight_id = $matrix[$b->ret_flight_id];
                $b->save();
                $this->info('Second Batch Booking '. $b->id . ' moved successfully! ');
            }

            $bookings = \App\Models\Ticket::query();
            $bookings->whereIn('flight_id', array_keys($matrix));
            $tickets = $bookings->get();

            foreach($tickets as $ticket) {
                $ticket->flight_id = $matrix[$ticket->flight_id];
                $ticket->save();
                $this->info('Ticket '. $ticket->id . ' moved successfully! ');
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
        return CommandAlias::SUCCESS;
    }
}
