<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\ImportProductJob;
use App\Models\Product;
use App\Services\ProductCreator;
use Exception;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ImportProductJobTest extends TestCase
{
    public function test_handle_success(): void
    {
        $productDTO = $this->mockProductDTO();
        $otherProductDTO = $this->mockProductDTO();
        $productDTOs = collect([$productDTO, $otherProductDTO]);

        $productCreatorMock = $this->createMock(originalClassName: ProductCreator::class);
        $productCreatorMock
            ->expects(self::exactly(2))
            ->method('create')
            ->willReturn(Product::factory()->make());

        $importProduct = new ImportProductJob(productDTOs: $productDTOs);
        $importProduct->handle(productCreator: $productCreatorMock);

        self::addToAssertionCount(1);
    }

    public function test_handle_fail(): void
    {
        $productDTO = $this->mockProductDTO();
        $otherProductDTO = $this->mockProductDTO();
        $productDTOs = collect([$productDTO, $otherProductDTO]);

        $productCreatorMock = $this->createMock(originalClassName: ProductCreator::class);
        $productCreatorMock
            ->expects(self::exactly(2))
            ->method('create')
            ->willReturnOnConsecutiveCalls(
                Product::factory()->make(),
                $this->throwException(new Exception()),
            );

        self::expectException(Exception::class);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollBack')->once();

        $importProduct = new ImportProductJob(productDTOs: $productDTOs);
        $importProduct->handle(productCreator: $productCreatorMock);
    }
}
