<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ProductDTO;
use App\DTO\TagDTO;
use App\Models\Product;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductCreator
{
    public function create(ProductDTO $productDTO): Product
    {
        try {
            DB::beginTransaction();

            $tagIds = $this->createTags(tags: $productDTO->getTags());

            $product = $this->createProduct($productDTO);
            $product->tags()->attach(id: $tagIds);

            DB::commit();

            return $product;
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    /**
     * @param  Collection<string,TagDTO>  $tags
     * @return int[]
     */
    private function createTags(Collection $tags): array
    {
        return $tags
            ->map(callback: static function (TagDTO $tagDTO): Tag {
                return Tag::updateOrCreate(
                    [
                        'title' => $tagDTO->getTitle(),
                    ],
                );
            })
            ->pluck(value: 'id')
            ->toArray();
    }

    private function createProduct(ProductDTO $productDTO): Product
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
