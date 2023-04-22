<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use _PHPStan_532094bc1\Nette\Neon\Exception;
use App\Exceptions\ProductImportFailed;
use App\Jobs\ImportProductJob;
use App\Models\Product;
use App\Services\ProductCreator;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ImportProductJobTest extends TestCase
{
    public function test_handle_success(): void
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

    public function test_handle_fail(): void
    {
        $productDTO = $this->mockProductDTO();
        $exceptionMessage = fake()->text;

        $productCreatorMock = $this->createMock(originalClassName: ProductCreator::class);
        $productCreatorMock
            ->method('create')
            ->with($productDTO)
            ->willThrowException(new Exception(message: $exceptionMessage));

        Log::shouldReceive('error')->with($exceptionMessage);

        self::expectException(ProductImportFailed::class);

        $importProduct = new ImportProductJob(productDTO: $productDTO);
        $importProduct->handle(productCreator: $productCreatorMock);

        self::addToAssertionCount(1);
    }
}
