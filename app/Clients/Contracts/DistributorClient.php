<?php

declare(strict_types=1);

namespace App\Clients\Contracts;

use App\DTO\ProductDTO;
use App\DTO\StockDTO;
use Illuminate\Support\Collection;

interface DistributorClient
{
    /**
     * @return Collection<int, ProductDTO>
     */
    public function fetchProducts(): Collection;

    /**
     * @return Collection<int,StockDTO>
     */
    public function fetchStocks(): Collection;
}
