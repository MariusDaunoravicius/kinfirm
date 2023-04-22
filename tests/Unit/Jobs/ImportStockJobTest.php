<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Exceptions\StockImportFailed;
use App\Jobs\ImportStockJob;
use App\Models\Stock;
use App\Services\StockCreator;
use Exception;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ImportStockJobTest extends TestCase
{
    public function test_handle_success(): void
    {
        $stockDTO = $this->mockStockDTO();

        $stockCreatorMock = $this->createMock(originalClassName: StockCreator::class);
        $stockCreatorMock
            ->method('create')
            ->with($stockDTO)
            ->willReturn(Stock::factory()->make());

        $importStockJob = new ImportStockJob(stockDTO: $stockDTO);
        $importStockJob->handle(stockCreator: $stockCreatorMock);

        self::addToAssertionCount(1);
    }

    public function test_handle_should_log_exception_on_fail(): void
    {
        $stockDTO = $this->mockStockDTO();
        $exceptionMessage = fake()->text;

        $stockCreatorMock = $this->createMock(originalClassName: StockCreator::class);
        $stockCreatorMock
            ->method('create')
            ->with($stockDTO)
            ->willThrowException(new Exception(message: $exceptionMessage));

        Log::shouldReceive('error')->with($exceptionMessage);

        self::expectException(StockImportFailed::class);

        $importStockJob = new ImportStockJob(stockDTO: $stockDTO);
        $importStockJob->handle(stockCreator: $stockCreatorMock);

        self::addToAssertionCount(1);
    }
}
