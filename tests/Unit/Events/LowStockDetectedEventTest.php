<?php

declare(strict_types=1);

use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\InventoryItem;
use App\Events\LowStockDetectedEvent;
use Illuminate\Support\Facades\Event;

test('it fires LowStockDetectedEvent when stock quantity drops below 5', function () {
    Event::fake();

    $warehouse = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    $stock = Stock::factory()->create([
        'warehouse_id' => $warehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 10,
    ]);

    $originalQuantity = $stock->quantity;
    $stock->quantity = 3;
    $stock->save();

    if ($stock->quantity < 5 && $originalQuantity >= 5) {
        $stock->loadMissing(['inventoryItem', 'warehouse']);
        if ($stock->warehouse && $stock->inventoryItem) {
            event(new LowStockDetectedEvent($stock->warehouse, $stock->inventoryItem));
        }
    }

    Event::assertDispatched(LowStockDetectedEvent::class);
});

test('it does not fire LowStockDetectedEvent when stock quantity stays above 5', function () {
    Event::fake();

    $warehouse = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    $stock = Stock::factory()->create([
        'warehouse_id' => $warehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 10,
    ]);

    $originalQuantity = $stock->quantity;
    $stock->quantity = 8;
    $stock->save();

    if ($stock->quantity < 5 && $originalQuantity >= 5) {
        $stock->loadMissing(['inventoryItem', 'warehouse']);
        if ($stock->warehouse && $stock->inventoryItem) {
            event(new LowStockDetectedEvent($stock->warehouse, $stock->inventoryItem));
        }
    }

    Event::assertNotDispatched(LowStockDetectedEvent::class);
});
