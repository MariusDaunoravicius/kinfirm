<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DTO\ProductDTO;
use App\Models\Product;
use App\Services\ProductCreator;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ImportProductJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  Collection<int,ProductDTO>  $productDTOs
     */
    public function __construct(
        private readonly Collection $productDTOs,
    ) {
    }

    public function handle(ProductCreator $productCreator): void
    {
        try {
            DB::beginTransaction();

            $this->productDTOs->each(fn (ProductDTO $productDTO): Product => $productCreator->create(productDTO: $productDTO));

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
