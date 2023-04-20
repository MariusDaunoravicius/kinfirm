<?php

declare(strict_types=1);

namespace App\Clients\Contracts;

use App\DTO\ProductDTO;
use Illuminate\Support\Collection;

interface DistributorClient
{
    /**
     * @return Collection<int, ProductDTO>
     */
    public function fetchProducts(): Collection;
}
