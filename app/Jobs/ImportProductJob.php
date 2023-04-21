<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DTO\ProductDTO;
use App\Exceptions\ProductImportFailed;
use App\Services\ProductCreator;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly ProductDTO $productDTO,
    ) {
    }

    public function handle(ProductCreator $productCreator): void
    {
        try {
            $productCreator->create($this->productDTO);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            throw new ProductImportFailed(
                message: sprintf('Failed to import product with SKU [%s]', $this->productDTO->getSKU()),
                previous: $exception,
            );
        }
    }
}
