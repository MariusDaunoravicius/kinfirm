<?php

declare(strict_types=1);

namespace App\Factories;

use App\DTO\ProductDTO;
use Carbon\Carbon;

class ProductDTOFactory
{
    public function __construct(private readonly TagDTOFactory $tagDTOFactory)
    {
    }

    /**
     * @param  array{sku:string, description:string, size:string, photo:string, updated_at:string, tags:array<string, string>}  $data
     */
    public function createFromArray(array $data): ProductDTO
    {
        return new ProductDTO(
            sku: $data['sku'],
            description: $data['description'],
            size: $data['size'],
            photo: $data['photo'],
            tags: collect($data['tags'])->map(callback: [$this->tagDTOFactory, 'createFromArray']),
            updatedAt: Carbon::createFromFormat(format: 'Y-m-d', time: $data['updated_at']) ?: null,
        );
    }
}
