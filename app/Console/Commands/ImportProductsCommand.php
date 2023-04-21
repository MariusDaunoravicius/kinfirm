<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Clients\Contracts\DistributorClient;
use App\DTO\ProductDTO;
use App\Jobs\ImportProductJob;
use Illuminate\Console\Command;

class ImportProductsCommand extends Command
{
    protected $signature = 'app:import-products';

    protected $description = 'Imports products from URL';

    public function __construct(
        private readonly DistributorClient $distributorClient
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $products = $this->distributorClient->fetchProducts();
        $products->each(callback: static fn (ProductDTO $productDTO) => ImportProductJob::dispatch($productDTO));
    }
}
