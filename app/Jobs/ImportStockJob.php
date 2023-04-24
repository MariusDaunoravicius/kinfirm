<?php

namespace App\Jobs;

use App\DTO\StockDTO;
use App\Models\Stock;
use App\Services\StockCreator;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ImportStockJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  Collection<int,StockDTO>  $stockDTOs
     */
    public function __construct(private readonly Collection $stockDTOs)
    {
    }

    public function handle(StockCreator $stockCreator): void
    {
        try {
            DB::beginTransaction();

            $this->stockDTOs->each(fn (StockDTO $stockDTO): Stock => $stockCreator->create(stockDTO: $stockDTO));

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
