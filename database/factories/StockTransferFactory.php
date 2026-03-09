<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Warehouse;
use App\Models\InventoryItem;
use App\Models\StockTransfer;

/**
 * @extends BaseFactory<StockTransfer>
 */
class StockTransferFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from_warehouse_id' => Warehouse::factory(),
            'to_warehouse_id' => Warehouse::factory(),
            'inventory_item_id' => InventoryItem::factory(),
            'quantity' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Create transfer from a specific warehouse.
     */
    public function fromWarehouse(Warehouse $warehouse): static
    {
        return $this->state(fn (array $attributes) => [
            'from_warehouse_id' => $warehouse->id,
        ]);
    }

    /**
     * Create transfer to a specific warehouse.
     */
    public function toWarehouse(Warehouse $warehouse): static
    {
        return $this->state(fn (array $attributes) => [
            'to_warehouse_id' => $warehouse->id,
        ]);
    }

    /**
     * Create transfer for a specific inventory item.
     */
    public function forInventoryItem(InventoryItem $inventoryItem): static
    {
        return $this->state(fn (array $attributes) => [
            'inventory_item_id' => $inventoryItem->id,
        ]);
    }
}
