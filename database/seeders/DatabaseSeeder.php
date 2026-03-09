<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\InventoryItem;
use App\Models\StockTransfer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 1 warehouse
        $warehouses = Warehouse::factory(2)
            ->create();

        // Create 5 inventory items
        $inventoryItems = InventoryItem::factory(5)->create();

        // Safely take up to 5 items (even if fewer exist)
        $items = $inventoryItems->shuffle()->take(5);

        foreach ($warehouses as $warehouse) {
            foreach ($items as $item) {
                Stock::factory()
                    ->forWarehouse($warehouse)
                    ->forInventoryItem($item)
                    ->create();
            }
        }

        // Create 3 stock transfers
        for ($i = 0; $i < 3; $i++) {
            $from = $warehouses->random();
            $to = $warehouses->where('id', '!=', $from->id)->random(); // ensure different warehouse
            $item = $inventoryItems->random();

            StockTransfer::factory()->create([
                'from_warehouse_id' => $from->id,
                'to_warehouse_id' => $to->id,
                'inventory_item_id' => $item->id,
                'quantity' => fake()->numberBetween(1, 20),
            ]);
        }
    }
}
