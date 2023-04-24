<?php

namespace App\Http\Controllers\V1\Products;

use App\Http\Resources\V1\ProductResource;
use App\Services\ProductFetcher;

class ShowController
{
    public function __invoke(string $sku, ProductFetcher $productFetcher): ProductResource
    {
        $product = $productFetcher->fetch($sku);

        return new ProductResource($product);
    }
}
