<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\DTO\ProductDTO;
use App\Services\ProductCreator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ProductCreatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_create(): void
    {
        $sku = fake()->word;
        $description = fake()->word;
        $size = fake()->word;
        $photo = fake()->imageUrl;
        $updatedAt = new Carbon();

        $productDTO = new ProductDTO(
            sku: $sku,
            description: $description,
            size: $size,
            photo: $photo,
            tags: collect(),
            updatedAt: $updatedAt,
        );

        $product = (new ProductCreator())->create($productDTO);

        self::assertEquals(
            [
                'sku' => $sku,
                'description' => $description,
                'size' => $size,
                'photo' => $photo,
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
            $updatedAt->format('Y-m-d'),
            $product->product_updated_at->format('Y-m-d'),
        );

        self::assertDatabaseHas(table: 'products', data: ['sku' => $sku]);
    }
}
