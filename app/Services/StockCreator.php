<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\StockDTO;
use App\Models\City;
use App\Models\Stock;
use Exception;
use Illuminate\Support\Facades\DB;

class StockCreator
{
    public function create(StockDTO $stockDTO): Stock
    {
        try {
            DB::beginTransaction();

            $city = $this->createCity(name: $stockDTO->getCity());
            $stock = $this->createStock(
                cityId: $city->id,
                sku: $stockDTO->getSKU(),
                stock: $stockDTO->getStock(),
            );

            DB::commit();

            return $stock;
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    private function createCity(string $name): City
    {
        return City::updateOrCreate(
            [
                'name' => $name,
            ],
        );
    }

    private function createStock(string $cityId, string $sku, int $stock): Stock
    {
        return Stock::updateOrCreate(
            [
                'sku' => $sku,
                'city_id' => $cityId,
            ],
            [
                'stock' => $stock,
            ],
        );
    }
}
