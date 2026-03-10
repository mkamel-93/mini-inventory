<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\StockTransfer;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @return array<string, mixed>
 *
 * @property StockTransfer $resource
 */
class StockTransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'fromWarehouse' => $this->resource->fromWarehouse,
            'toWarehouse' => $this->resource->toWarehouse,
            'inventoryItem' => $this->resource->inventoryItem,
            'quantity' => $this->resource->quantity,
        ];
    }
}
