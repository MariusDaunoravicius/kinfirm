<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\Tag;
use App\Services\ProductCreator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCreatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_success(): void
    {
        $tagDTO = $this->mockTagDTO();
        $tags = collect([$tagDTO]);
        $productDTO = $this->mockProductDTO(tags: $tags);

        $product = (new ProductCreator())->create(productDTO: $productDTO);

        self::assertEquals(
            [
                'sku' => $productDTO->getSKU(),
                'description' => $productDTO->getDescription(),
                'size' => $productDTO->getSize(),
                'photo' => $productDTO->getPhoto(),
            ],
            $product->only(
                attributes: [
                    'sku',
                    'description',
                    'size',
                    'photo',
                ],
            )
        );

        self::assertEquals(
            expected: $productDTO->getUpdatedAt()->format(format: 'Y-m-d'),
            actual: $product->product_updated_at->format(format: 'Y-m-d'),
        );

        self::assertDatabaseHas(table: 'products', data: ['sku' => $productDTO->getSKU()]);
        self::assertDatabaseHas(table: 'tags', data: ['title' => $tagDTO->getTitle()]);
        $this->assertDatabaseHas(
            'product_tag',
            [
                'product_id' => Product::first()->id,
                'tag_id' => Tag::first()->id,
            ],
        );
    }
}
