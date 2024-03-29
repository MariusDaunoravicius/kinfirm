<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Clients\Contracts\DistributorClient;
use App\Jobs\ImportStockJob;
use App\Services\ProductFetcher;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;

class ImportStocksCommand extends Command
{
    private const BATCH_SIZE = 10;

    protected $signature = 'app:import-stocks';

    protected $description = 'Imports stocks from distributor API';

    public function __construct(
        private readonly DistributorClient $distributorClient
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $stocks = $this->distributorClient->fetchStocks();
        $chunks = $stocks->chunk(size: self::BATCH_SIZE);
        $jobs = $chunks->map(callback: static fn (Collection $stockDTOs) => new ImportStockJob($stockDTOs));

        Bus::batch($jobs)
            ->name('import-stocks')
            ->finally(function (Batch $batch) {
                Cache::tags(names: [ProductFetcher::STOCK_TAG_CACHE_KEY])->flush();
            })->dispatch();
    }
}
