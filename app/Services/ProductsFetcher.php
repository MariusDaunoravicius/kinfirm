<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductsFetcher
{
    private const PRODUCTS_PER_PAGE = 20;

    public function fetch(int $perPage = self::PRODUCTS_PER_PAGE): LengthAwarePaginator
    {
        return Product::with(
            relations: [
                'tags',
                'stocks.city',
            ],
        )->paginate(
            perPage: $perPage,
        );
    }
}
