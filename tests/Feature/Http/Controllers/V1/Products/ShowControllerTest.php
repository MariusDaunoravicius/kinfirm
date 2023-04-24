<?php

namespace Tests\Feature\Http\Controllers\V1\Products;

use App\Http\Controllers\V1\Products\ShowController;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Tag;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\getJson;
use Symfony\Component\HttpFoundation\Response;

it('should return product data', function (): void {
    $product = Product::factory()->create();
    $tag = Tag::factory()->create();
    $stock = Stock::factory()->withCity()->create(['sku' => $product->sku]);
    $product->tags()->attach(id: [$tag->id]);

    getJson(
        uri: action(ShowController::class, ['sku' => $product->sku]),
    )->assertStatus(
        status: Response::HTTP_OK,
    )->assertJson(
        fn (AssertableJson $json) => $json
            ->has('data')
            ->where(
                key: 'data',
                expected: [
                    'sku' => $product->sku,
                    'description' => $product->description,
                    'photo' => $product->photo,
                    'size' => $product->size,
                    'stock' => [
                        [
                            'sku' => $stock->sku,
                            'stock' => $stock->stock,
                            'city' => [
                                'name' => $stock->city->name,
                            ],
                        ],
                    ],
                    'tags' => [
                        [
                            'title' => $tag->title,
                        ],
                    ],
                    'updated_at' => $product->product_updated_at->format('Y-m-d'),
                ],
            )
            ->etc(),
    );
});
