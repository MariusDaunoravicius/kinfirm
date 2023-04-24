<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Clients\Contracts\DistributorClient;
use App\Jobs\ImportProductJob;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

class ImportProductsCommand extends Command
{
    public const BATCH_SIZE = 10;

    protected $signature = 'app:import-products';

    protected $description = 'Imports products from distributor API';

    public function __construct(
        private readonly DistributorClient $distributorClient
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $products = $this->distributorClient->fetchProducts();
        $chunks = $products->chunk(size: self::BATCH_SIZE);
        $jobs = $chunks->map(callback: static fn (Collection $productDTOs) => new ImportProductJob($productDTOs));

        Bus::batch($jobs)
            ->name('import-products')
            ->finally(function (Batch $batch) {
                // TODO:  Clear cache
            })->dispatch();
    }
}
