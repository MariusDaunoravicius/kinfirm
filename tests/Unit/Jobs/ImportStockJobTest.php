<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\ImportStockJob;
use App\Models\Stock;
use App\Services\StockCreator;
use Exception;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ImportStockJobTest extends TestCase
{
    public function test_handle_success(): void
    {
        $stockDTO = $this->mockStockDTO();
        $otherStockDTO = $this->mockStockDTO();
        $stockDTOs = collect([$stockDTO, $otherStockDTO]);

        $stockCreatorMock = $this->createMock(originalClassName: StockCreator::class);
        $stockCreatorMock
            ->expects(self::exactly(2))
            ->method('create')
            ->willReturn(Stock::factory()->make());

        $importStockJob = new ImportStockJob(stockDTOs: $stockDTOs);
        $importStockJob->handle(stockCreator: $stockCreatorMock);
    }

    public function test_handle_should_log_exception_on_fail(): void
    {
        $stockDTO = $this->mockStockDTO();
        $otherStockDTO = $this->mockStockDTO();
        $stockDTOs = collect([$stockDTO, $otherStockDTO]);

        $stockCreatorMock = $this->createMock(originalClassName: StockCreator::class);
        $stockCreatorMock
            ->expects(self::exactly(2))
            ->method('create')
            ->willReturnOnConsecutiveCalls(
                Stock::factory()->make(),
                $this->throwException(new Exception()),
            );

        self::expectException(Exception::class);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollBack')->once();

        $importStockJob = new ImportStockJob(stockDTOs: $stockDTOs);
        $importStockJob->handle(stockCreator: $stockCreatorMock);
    }
}
