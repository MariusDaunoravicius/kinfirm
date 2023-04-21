<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DTO\ProductDTO;
use App\Services\ProductCreator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly ProductDTO $productDTO,
    ) {
    }

    public function handle(ProductCreator $productCreator): void
    {
        $productCreator->create($this->productDTO);
    }
}
