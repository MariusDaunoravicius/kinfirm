<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DTO\ProductDTO;
use App\Exceptions\ProductImportFailed;
use App\Services\ProductCreator;
use App\Services\TagCreator;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly ProductDTO $productDTO,
    ) {
    }

    public function handle(ProductCreator $productCreator, TagCreator $tagCreator): void
    {
        DB::beginTransaction();
        try {
            $tags = $this->productDTO->getTags()->map(callback: [$tagCreator, 'create']);
            $tagIds = $tags->pluck(value: 'id');

            $product = $productCreator->create(productDTO: $this->productDTO);
            $product->tags()->attach(id: $tagIds);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw new ProductImportFailed(
                message: 'Failed to import product with SKU ['.$this->productDTO->getSKU().']',
                previous: $exception,
            );
        }
    }
}
