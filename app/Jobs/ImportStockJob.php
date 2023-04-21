<?php

namespace App\Jobs;

use App\DTO\StockDTO;
use App\Exceptions\StockImportFailed;
use App\Services\StockCreator;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportStockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly StockDTO $stockDTO) {}

    public function handle(StockCreator $stockCreator): void
    {
        try {
            $stockCreator->create($this->stockDTO);
        } catch (Exception $exception) {
            $message = sprintf(
                'Failed to import stock with SKU [%s], city [%s]',
                $this->stockDTO->getSKU(),
                $this->stockDTO->getCity(),
            );

            Log::error($exception->getMessage());

            throw new StockImportFailed(
                message: $message,
                previous: $exception,
            );
        }
    }
}
