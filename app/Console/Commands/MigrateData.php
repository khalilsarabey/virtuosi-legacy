<?php

namespace App\Console\Commands;

use App\Models\Booking;
use DB;
use Illuminate\Console\Command;
use Log;
use Symfony\Component\Console\Command\Command as CommandAlias;

class MigrateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Start a DB transaction
        DB::beginTransaction();

        try {
            // Fetch bookings with relationships
            $bookings = Booking::with([
                'depFlight.depFrom',
                'depFlight.airline',
                'depFlight.depTo',
                'retFlight.depFrom',
                'retFlight.depTo',
                'retFlight.airline',
                'depFlight.airline',
                'retFlight.airline'
            ])->get();

            // Check if bookings are fetched
            if ($bookings->isEmpty()) {
                $this->info("No bookings found.");
                return;
            }

            foreach ($bookings as $booking) {
                $booking->dep_airline_name = $booking->depFlight?->airline?->name;
                $booking->dep_airline_logo = $booking->depFlight?->airline?->thumbnail;
                $booking->dep_airline_code = $booking->depFlight?->airline?->icao;

                $booking->ret_airline_name = $booking->retFlight?->airline?->name;
                $booking->ret_airline_logo = $booking->retFlight?->airline?->thumbnail;
                $booking->ret_airline_code = $booking->retFlight?->airline?->icao;

                $booking->dep_time         = $booking->depFlight?->dep_time; // Changed to depFlight
                $booking->dep_arr_time     = $booking->depFlight?->dep_arr_time; // Changed to depFlight

                $booking->ret_dep_time     = $booking->retFlight?->dep_time; // Changed to retFlight
                $booking->ret_arr_time     = $booking->retFlight?->arr_time; // Changed to retFlight

                if($booking->source == 'api') {
                    $booking->connection_id = 4;
                    $booking->connection_name = 'API';
                }

                // Try to save the booking
                $booking->save();
                $this->info("Booking {$booking->id} updated successfully.");
            }

            // Commit the transaction
            DB::commit();
            $this->info("Command executed successfully.");
        } catch(\Exception $e) {
            // Rollback the transaction
            DB::rollBack();
            $this->error("Command failed to execute: " . $e->getMessage());
        }
    }

}
