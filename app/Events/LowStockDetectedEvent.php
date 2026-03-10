<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Warehouse;
use App\Models\InventoryItem;

class LowStockDetectedEvent extends BaseEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Warehouse $warehouse,
        public InventoryItem $inventoryItem
    ) {
        //
    }
}
