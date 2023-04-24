<?php

namespace App\Http\Controllers\V1\Products;

use App\Http\Resources\V1\ProductResource;
use App\Services\ProductsFetcher;
use Illuminate\Http\Resources\Json\ResourceCollection;

class IndexController
{
    public function __invoke(ProductsFetcher $productsFetcher): ResourceCollection
    {
        $products = $productsFetcher->fetch();

        return ProductResource::collection($products);
    }
}
