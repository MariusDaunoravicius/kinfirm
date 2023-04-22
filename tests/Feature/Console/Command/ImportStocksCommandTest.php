<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Command;

use App\Console\Commands\ImportStocksCommand;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ImportStocksCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_import(): void
    {
        $stock = [
            'sku' => fake()->word,
            'stock' => fake()->randomNumber(2),
            'city' => fake()->city,
        ];

        Http::fake([
            'https://kinfirm.com/app/uploads/laravel-task/stocks.json' => Http::response([$stock]),
        ]);

        /** @var ImportStocksCommand $importStocksCommand */
        $importStocksCommand = $this->app->get(ImportStocksCommand::class);

        $importStocksCommand->handle();

        $this->assertDatabaseHas(
            'stocks',
            [
                'city_id' => City::first()->id,
                'sku' => $stock['sku'],
                'stock' => $stock['stock'],
            ],
        );

        $this->assertDatabaseHas(
            'cities',
            [
                'name' => $stock['city'],
            ],
        );
    }
}
