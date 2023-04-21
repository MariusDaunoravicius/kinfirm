<?php

declare(strict_types=1);

namespace App\Factories;

use App\DTO\StockDTO;

class StockDTOFactory
{
    /**
     * @param  array{sku:string, stock:int, city:string}  $data
     */
    public function createFromArray(array $data): StockDTO
    {
        return new StockDTO(
            sku: $data['sku'],
            stock: (int) $data['stock'],
            city: $data['city'],
        );
    }
}
