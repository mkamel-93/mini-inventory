<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Warehouse $resource
 *
 * @return array<string, mixed>
 */
class WarehouseResource extends JsonResource
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
            'name' => $this->resource->name,
            'location' => $this->resource->location,
            'inventoryItems' => InventoryItemResource::collection($this->whenLoaded('inventoryItems')),
            'transfers' => [
                'outgoing' => StockTransferResource::collection($this->whenLoaded('stockTransfersFrom')),
                'incoming' => StockTransferResource::collection($this->whenLoaded('stockTransfersTo')),
            ],
        ];
    }
}
