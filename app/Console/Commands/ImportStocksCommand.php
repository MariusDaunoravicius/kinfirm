<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Clients\Contracts\DistributorClient;
use App\DTO\StockDTO;
use App\Jobs\ImportStockJob;
use Illuminate\Console\Command;

class ImportStocksCommand extends Command
{
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
        $stocks->each(callback: static fn (StockDTO $stockDTO) => ImportStockJob::dispatch($stockDTO));
    }
}
