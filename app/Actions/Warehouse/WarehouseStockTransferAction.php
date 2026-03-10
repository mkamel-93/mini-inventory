<?php

declare(strict_types=1);

namespace App\Actions\Warehouse;

use Throwable;
use App\Models\Stock;
use App\Facades\ToggleCache;
use App\Models\StockTransfer;
use Illuminate\Http\Response;
use App\DTOs\StockTransferDTO;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\API\Warehouse\WarehouseStockTransferRequest;

class WarehouseStockTransferAction
{
    use ApiResponseTrait;

    /**
     * Execute the stock transfer between warehouses.
     *
     *
     * @throws Throwable
     */
    public function __invoke(WarehouseStockTransferRequest $request, WarehouseStockTransferAction $action): JsonResponse
    {
        try {
            $dto = StockTransferDTO::fromRequest($request);
            $result = DB::transaction(function () use ($dto) {
                /** @var Stock|null $fromStock */
                $fromStock = Stock::where([
                    'warehouse_id' => $dto->from_warehouse_id,
                    'inventory_item_id' => $dto->inventory_item_id,
                ])->lockForUpdate()->first();

                if (! $fromStock || $fromStock->quantity < $dto->quantity) {
                    throw ValidationException::withMessages([
                        'quantity' => ['Insufficient stock at source warehouse.'],
                    ]);
                }

                // Update Source Stock
                $fromStock->quantity -= $dto->quantity;
                $fromStock->save();

                // Update or Create Destination Stock
                $toStock = Stock::firstOrNew([
                    'warehouse_id' => $dto->to_warehouse_id,
                    'inventory_item_id' => $dto->inventory_item_id,
                ]);

                $toStock->quantity = (int) ($toStock->quantity ?? 0) + $dto->quantity;
                $toStock->save();

                // Record the transfer
                StockTransfer::create($dto->toArray());

                // Invalidate Caches for both warehouses
                ToggleCache::forget("warehouse_inv_{$dto->from_warehouse_id}");
                ToggleCache::forget("warehouse_inv_{$dto->to_warehouse_id}");

                return [
                    'from_quantity' => (int) $fromStock->quantity,
                    'to_quantity' => (int) $toStock->quantity,
                ];
            });

            return $this->success(
                message: "From Source Quantity after Decrement [{$result['from_quantity']}] To Source Quantity after Increment [{$result['to_quantity']}]",
                data: $result,
                code: Response::HTTP_CREATED
            );
        } catch (ValidationException $e) {
            return $this->error(
                message: $e->getMessage(),
                code: Response::HTTP_UNPROCESSABLE_ENTITY,
                errors: $e->errors()
            );
        }
    }
}
