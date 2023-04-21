<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Command;

use App\Console\Commands\ImportProductsCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ImportProductsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_import(): void
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


        /** @var ImportProductsCommand $importProductsCommand */
        $importProductsCommand = $this->app->get(ImportProductsCommand::class);

        $importProductsCommand->handle();

        $this->assertDatabaseHas(
            'products',
            [
                'sku' => $product['sku'],
                'description' => $product['description'],
                'size' => $product['size'],
                'photo' => $product['photo'],
            ],
        );
    }
}