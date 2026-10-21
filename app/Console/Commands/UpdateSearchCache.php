<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateSearchCache extends Command
{
    protected $signature = 'cache:update-search';
    protected $description = 'Command description';
    public function handle()
    {
        // Call the helper function
        getSearchData();
        $this->info('Search data cache updated.');
    }
}
