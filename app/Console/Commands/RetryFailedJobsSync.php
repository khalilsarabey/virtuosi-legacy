<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Log;
use Throwable;

class RetryFailedJobsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:retry-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry all failed jobs and run them immediately (sync-style)';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $failedJobs = DB::table('failed_jobs')->orderBy('id')->get();

        if ($failedJobs->isEmpty()) {
            $this->info('No failed jobs to retry.');
            return 0;
        }

        foreach ($failedJobs as $failed) {
            $payload = json_decode($failed->payload, true);

            if (!isset($payload['data']['command'])) {
                $this->error("Job ID {$failed->id} does not have a valid command.");
                continue;
            }

            try {
                $jobInstance = unserialize($payload['data']['command']);

                $this->info("Retrying Job ID {$failed->id}: " . get_class($jobInstance));

                $jobInstance->handle(); // Runs the job immediately

                DB::table('failed_jobs')->where('id', $failed->id)->delete();

                $this->info("Job ID {$failed->id} completed and removed from failed_jobs.");
            } catch (Throwable $e) {
                $this->error("Failed again: Job ID {$failed->id}: " . $e->getMessage());
                Log::error("Failed job retry error (ID {$failed->id}): " . $e->getMessage());
            }
        }

        return 0;
    }
}
