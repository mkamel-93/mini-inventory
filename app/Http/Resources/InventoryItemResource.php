<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\InventoryItem;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @return array<string, mixed>
 *
 * @property InventoryItem $resource
 */
class InventoryItemResource extends JsonResource
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
            'description' => $this->resource->description,
            'sku' => $this->resource->sku,
            'price' => $this->resource->price,
            'quantity' => $this->whenPivotLoaded('stocks', function () {
                return $this->resource->pivot?->quantity;
            }),
        ];
    }
}
