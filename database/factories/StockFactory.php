<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\InventoryItem;

/**
 * @extends BaseFactory<Stock>
 */
class StockFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'warehouse_id' => Warehouse::factory(),
            'inventory_item_id' => InventoryItem::factory(),
            'quantity' => fake()->numberBetween(0, 10),
        ];
    }

    /**
     * Create stock for a specific warehouse.
     */
    public function forWarehouse(Warehouse $warehouse): static
    {
        return $this->state(fn (array $attributes) => [
            'warehouse_id' => $warehouse->id,
        ]);
    }

    /**
     * Create stock for a specific inventory item.
     */
    public function forInventoryItem(InventoryItem $inventoryItem): static
    {
        return $this->state(fn (array $attributes) => [
            'inventory_item_id' => $inventoryItem->id,
        ]);
    }
}
