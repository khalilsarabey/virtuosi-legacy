<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class MoveFlights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flights:move';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process flights data';

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
                $main->with('bookings');
                $result = $main->first();
                $result->seats = $result->seats + $flight->seats;
                $result->save();
                $this->info('Flight '. $flight->id . ' moved successfully! ');
                foreach($flight->bookings as $booking) {
                    $booking->flight_id = $result->id;
                    $booking->dep_flight_id = $result->id;
                    $booking->save();
                    $this->info('Booking '. $booking->id . ' moved successfully! ');
                }
                $this->info('Extra flights fetched successfully! ' . $extraFlights->count());
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->info($e->getMessage());
        }
        return CommandAlias::SUCCESS;
    }
}
