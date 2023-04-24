<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductFetcher
{
    public const PRODUCT_TAG_CACHE_KEY = 'products';

    public const STOCK_TAG_CACHE_KEY = 'stocks';

    private const HOUR_IN_SECONDS = 3600;

    private const PRODUCT_CACHE_KEY_PREFIX = 'product-';

    private const STOCK_CACHE_KEY_PREFIX = 'stock-';

    public function fetch(string $sku): Product
    {
        $product = $this->getProduct($sku);
        $stocks = $this->getStocks($sku);
        $product->setRelation(relation: 'stocks', value: $stocks->values());

        return $product;
    }

    private function getProductCacheKey(string $sku): string
    {
        return self::PRODUCT_CACHE_KEY_PREFIX.$sku;
    }

    private function getStockCacheKey(string $sku): string
    {
        return self::STOCK_CACHE_KEY_PREFIX.$sku;
    }

    private function getProduct(string $sku): Product
    {
        return Cache::tags(names: [self::PRODUCT_TAG_CACHE_KEY])
            ->remember(
                key: $this->getProductCacheKey($sku),
                ttl: self::HOUR_IN_SECONDS,
                callback: function () use ($sku): Product {
                    return Product::where('sku', $sku)->firstOrFail();
                },
            );
    }

    /**
     * @return Collection<int,Stock>
     */
    private function getStocks(string $sku): Collection
    {
        return Cache::tags(names: [self::STOCK_TAG_CACHE_KEY])
            ->remember(
                key: $this->getStockCacheKey($sku),
                ttl: self::HOUR_IN_SECONDS,
                callback: function () use ($sku): Collection {
                    return Stock::where('sku', $sku)->get();
                },
            );
    }
}
