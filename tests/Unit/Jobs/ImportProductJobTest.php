<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\ImportProductJob;
use App\Models\Product;
use App\Services\ProductCreator;
use Tests\TestCase;

class ImportProductJobTest extends TestCase
{
    public function test_handle(): void
    {
        $productDTO = $this->mockProductDTO();

        $productCreatorMock = $this->createMock(originalClassName: ProductCreator::class);
        $productCreatorMock
            ->method('create')
            ->with($productDTO)
            ->willReturn(Product::factory()->make());

        $importProduct = new ImportProductJob(productDTO: $productDTO);
        $importProduct->handle(productCreator: $productCreatorMock);

        self::addToAssertionCount(1);
    }
}
