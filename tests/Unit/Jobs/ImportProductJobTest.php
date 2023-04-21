<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Exceptions\ProductImportFailed;
use App\Jobs\ImportProductJob;
use App\Models\Product;
use App\Models\Tag;
use App\Services\ProductCreator;
use App\Services\TagCreator;
use DB;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class ImportProductJobTest extends TestCase
{
    private MockObject|ProductCreator $productCreatorMock;
    private MockObject|TagCreator $tagCreatorMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->productCreatorMock = $this->createMock(originalClassName: ProductCreator::class);
        $this->tagCreatorMock = $this->createMock(originalClassName: TagCreator::class);
    }

    public function test_handle_successful(): void
    {
        $tagDTO = $this->mockTagDTO();
        $tags = collect([$tagDTO]);
        $productDTO = $this->mockProductDTO(tags: $tags);

        $importProduct = new ImportProductJob(productDTO: $productDTO);

        $tagMock = $this->createMock(Tag::class);

        $this->tagCreatorMock
            ->method('create')
            ->with($tagDTO)
            ->willReturn($tagMock);

        $productMock = $this->createMock(Product::class);

        $this->productCreatorMock
            ->expects(self::once())
            ->method('create')
            ->with($productDTO)
            ->willReturn($productMock);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        $importProduct->handle(productCreator: $this->productCreatorMock, tagCreator: $this->tagCreatorMock);
    }

    public function testHandleFail(): void
    {
        $tagDTO = $this->mockTagDTO();
        $tags = collect([$tagDTO]);
        $productDTO = $this->mockProductDTO(tags: $tags);

        $importProduct = new ImportProductJob(productDTO: $productDTO);

        $this->tagCreatorMock
            ->method('create')
            ->with($tagDTO)
            ->willThrowException(new Exception());

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollBack')->once();

        $this->expectException(ProductImportFailed::class);

        $importProduct->handle(productCreator: $this->productCreatorMock, tagCreator: $this->tagCreatorMock);
    }
}
