<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Commands;

use App\Clients\Contracts\DistributorClient;
use App\Console\Commands\ImportProductsCommand;
use App\Console\Commands\ImportStocksCommand;
use App\Jobs\ImportProductJob;
use App\Jobs\ImportStockJob;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ImportStocksCommandTest extends TestCase
{
    public function test_import(): void
    {
        $stockDTO = $this->mockStockDTO();
        $stocks = collect([$stockDTO]);

        $distributorClientMock = $this->createMock(DistributorClient::class);
        $distributorClientMock->expects(self::once())->method('fetchStocks')->willReturn($stocks);

        Bus::fake();

        (new ImportStocksCommand($distributorClientMock))->handle();

        Bus::assertDispatched(ImportStockJob::class);
    }
}
