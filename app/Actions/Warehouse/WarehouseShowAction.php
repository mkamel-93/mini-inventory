<?php

declare(strict_types=1);

namespace App\Actions\Warehouse;

use App\Models\Warehouse;
use App\Facades\ToggleCache;
use App\Http\Resources\WarehouseResource;

class WarehouseShowAction
{
    public function __invoke(Warehouse $warehouse): WarehouseResource
    {
        $data = ToggleCache::remember("warehouse_inv_{$warehouse->id}", function () use ($warehouse) {
            return $warehouse->loadMissing([
                'inventoryItems',
                'stockTransfersFrom',
                'stockTransfersTo',
            ]);
        });

        return WarehouseResource::make($data);
    }
}
