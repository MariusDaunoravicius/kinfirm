<?php

namespace App\Http\Resources\V1;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @var Product
     */
    public $resource;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'sku' => $this->resource->sku,
            'description' => $this->resource->description,
            'size' => $this->resource->size,
            'photo' => $this->resource->photo,
            'updated_at' => $this->resource->updated_at->format(format: 'Y-m-d'),
            'stock' => $this->resource->stocks->map(static fn (Stock $stock): StockResource => new StockResource($stock)),
        ];
    }
}
