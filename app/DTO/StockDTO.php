<?php

declare(strict_types=1);

namespace App\DTO;

class StockDTO
{
    public function __construct(
        private readonly string $sku,
        private readonly int $stock,
        private readonly string $city,
    ) {
    }

    public function getSKU(): string
    {
        return $this->sku;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getCity(): string
    {
        return $this->city;
    }
}
