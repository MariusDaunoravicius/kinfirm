<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\StockDTO;
use App\Exceptions\StockImportFailed;
use App\Models\City;
use App\Models\Stock;
use Exception;

class StockCreator
{
    public function create(StockDTO $stockDTO): Stock
    {
        try {
            $city = $this->createCity(name: $stockDTO->getCity());
            $stock = $this->createStock(
                cityId: $city->id,
                sku: $stockDTO->getSKU(),
                stock: $stockDTO->getStock(),
            );

            return $stock;
        } catch (Exception $exception) {
            throw new StockImportFailed(
                message: sprintf(
                    'Failed to import stock with SKU [%s], city [%s]',
                    $stockDTO->getSKU(),
                    $stockDTO->getCity(),
                ),
                previous: $exception,
            );
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
