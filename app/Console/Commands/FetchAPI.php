<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Services\CatalogInventorySyncService;

#[Signature('api:fetch')]
#[Description('Fetch catalog data from API (Library Owner)')]
class FetchAPI extends Command
{
    /**
     * Execute the console command.
     */

    public function handle(CatalogInventorySyncService $service)
    {
		$service->sync();

		$this->info('Catalog synchronized successfully.');

        return self::SUCCESS;
    }
}
