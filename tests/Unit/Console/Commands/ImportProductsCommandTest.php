<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Commands;

use App\Clients\Contracts\DistributorClient;
use App\Console\Commands\ImportProductsCommand;
use Illuminate\Bus\PendingBatch;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ImportProductsCommandTest extends TestCase
{
    public function test_import(): void
    {
        $productDTO = $this->mockProductDTO();
        $products = collect([$productDTO]);

        $distributorClientMock = $this->createMock(DistributorClient::class);
        $distributorClientMock->expects(self::once())->method('fetchProducts')->willReturn($products);

        Bus::fake();

        (new ImportProductsCommand($distributorClientMock))->handle();

        Bus::assertBatched(function (PendingBatch $batch) {
            return $batch->name == 'import-products' &&
                $batch->jobs->count() === 1;
        });
    }
}
