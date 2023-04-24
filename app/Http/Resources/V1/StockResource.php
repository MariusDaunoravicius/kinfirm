<?php

namespace App\Http\Resources\V1;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    /**
     * @var Stock
     */
    public $resource;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'sku' => $this->resource->sku,
            'stock' => $this->resource->stock,
            'city' => new CityResource($this->resource->city),
        ];
    }
}
