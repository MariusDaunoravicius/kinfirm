<?php

declare(strict_types=1);

namespace App\Clients;

use App\Clients\Contracts\DistributorClient;
use App\DTO\ProductDTO;
use App\DTO\StockDTO;
use App\Factories\ProductDTOFactory;
use App\Factories\StockDTOFactory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class DistributorHttpClient implements DistributorClient
{
    private const BASE_URL = 'https://kinfirm.com/app/uploads/laravel-task/';

    protected PendingRequest $httpClient;

    public function __construct(
        private readonly ProductDTOFactory $productDTOFactory,
        private readonly StockDTOFactory $stockDTOFactory,
    ) {
        $this->httpClient = Http::baseUrl(url: self::BASE_URL);
    }

    /**
     * @return Collection<int,ProductDTO>
     */
    public function fetchProducts(): Collection
    {
        $response = $this->httpClient->acceptJson()->get(url: 'products.json');

        return $response->collect()->map(callback: [$this->productDTOFactory, 'createFromArray']);
    }

    /**
     * @return Collection<int,StockDTO>
     */
    public function fetchStocks(): Collection
    {
        $response = $this->httpClient->acceptJson()->get(url: 'stocks.json');

        return $response->collect()->map(callback: [$this->stockDTOFactory, 'createFromArray']);
    }
}
