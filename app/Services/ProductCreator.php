<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ProductDTO;
use App\Models\Product;

class ProductCreator
{
    public function create(ProductDTO $productDTO): Product
    {
        return Product::updateOrCreate(
            [
                'sku' => $productDTO->getSKU(),
            ],
            [
                'description' => $productDTO->getDescription(),
                'size' => $productDTO->getSize(),
                'photo' => $productDTO->getPhoto(),
                'product_updated_at' => $productDTO->getUpdatedAt(),
            ]
        );
    }
}
