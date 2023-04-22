<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\City;
use App\Services\StockCreator;
use DB;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockCreatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_success(): void
    {
        $stockDTO = $this->mockStockDTO();

        $stock = (new StockCreator())->create(stockDTO: $stockDTO);

        self::assertEquals(
            [
                'sku' => $stockDTO->getSKU(),
                'stock' => $stockDTO->getStock(),
            ],
            $stock->only(
                attributes: [
                    'sku',
                    'stock',
                ],
            )
        );

        self::assertDatabaseHas(
            table: 'stocks',
            data: [
                'city_id' => City::first()->id,
                'sku' => $stockDTO->getSKU(),
                'stock' => $stockDTO->getStock(),
            ]
        );
        self::assertDatabaseHas(table: 'cities', data: ['name' => $stockDTO->getCity()]);
    }

    public function test_create_should_rollback_db_changes_on_exception(): void
    {
        $stockDTO = $this->mockStockDTO();

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once()->andThrowExceptions([new Exception()]);
        DB::shouldReceive('rollBack')->once();

        $this->expectException(Exception::class);

        (new StockCreator())->create(stockDTO: $stockDTO);

        self::assertDatabaseMissing(table: 'stocks', data: ['sku' => $stockDTO->getSKU()]);
    }
}
