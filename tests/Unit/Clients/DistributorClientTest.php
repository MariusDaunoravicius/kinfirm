<?php

declare(strict_types=1);

namespace Tests\Unit\Clients;

use App\Clients\DistributorHttpClient;
use App\DTO\ProductDTO;
use App\Factories\ProductDTOFactory;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DistributorClientTest extends TestCase
{
    public function test_fetch_products(): void
    {
        $product = [
            'sku' => fake()->word,
            'description' => fake()->word,
            'size' => fake()->word,
            'photo' => fake()->imageUrl,
            'tags' => [
                ['title' => fake()->word],
            ],
            'updated_at' => fake()->date,
        ];

        Http::fake([
            'https://kinfirm.com/app/uploads/laravel-task/products.json' => Http::response([$product]),
        ]);

        $productDTOMock = $this->createMock(ProductDTO::class);

        $productDTOFactoryMock = $this->createMock(ProductDTOFactory::class);
        $productDTOFactoryMock
            ->expects(self::once())
            ->method('createFromArray')
            ->with($product)
            ->willReturn($productDTOMock);

        $products = (new DistributorHttpClient($productDTOFactoryMock))->fetchProducts();

        self::assertEquals(collect([$productDTOMock]), $products);
    }
}
