<?php

declare(strict_types=1);

namespace Tests\Unit\Clients;

use App\Clients\DistributorHttpClient;
use App\DTO\ProductDTO;
use App\DTO\StockDTO;
use App\Factories\ProductDTOFactory;
use App\Factories\StockDTOFactory;
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

        $stockDTOFactoryMock = $this->createMock(StockDTOFactory::class);

        $productDTOFactoryMock = $this->createMock(ProductDTOFactory::class);
        $productDTOFactoryMock
            ->expects(self::once())
            ->method('createFromArray')
            ->with($product)
            ->willReturn($productDTOMock);

        $products = (new DistributorHttpClient($productDTOFactoryMock, $stockDTOFactoryMock))->fetchProducts();

        self::assertEquals(collect([$productDTOMock]), $products);
    }

    public function test_fetch_stock(): void
    {
        $stock = [
            'sku' => fake()->word,
            'stock' => fake()->randomNumber(2),
            'city' => fake()->city,
        ];

        Http::fake([
            'https://kinfirm.com/app/uploads/laravel-task/stocks.json' => Http::response([$stock]),
        ]);

        $stockDTOMock = $this->createMock(StockDTO::class);

        $stockDTOFactoryMock = $this->createMock(StockDTOFactory::class);
        $stockDTOFactoryMock
            ->expects(self::once())
            ->method('createFromArray')
            ->with($stock)
            ->willReturn($stockDTOMock);

        $productDTOFactoryMock = $this->createMock(ProductDTOFactory::class);

        $stocks = (new DistributorHttpClient($productDTOFactoryMock, $stockDTOFactoryMock))->fetchStocks();

        self::assertEquals(collect([$stockDTOMock]), $stocks);
    }
}
