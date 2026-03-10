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
     * Seed the application's database using external config data.
     */
    public function run(): void
    {
        $data = config('warehouse-data');

        // 1. Seed Warehouses
        foreach ($data['warehouses'] as $warehouse) {
            Warehouse::upsert($warehouse, ['name'], ['location']);
        }

        // 2. Seed Inventory Items
        foreach ($data['inventory_items'] as $item) {
            InventoryItem::upsert($item, ['id'], ['name', 'sku', 'price', 'description']);
        }

        // 3. Seed Stock Levels
        foreach ($data['stock'] as $stock) {
            Stock::upsert($stock, ['id'], ['warehouse_id', 'inventory_item_id', 'quantity']);
        }

        // 4. Seed Stock Transfers
        //        foreach ($data['stock_transfers'] as $transfer) {
        //            StockTransfer::upsert($transfer, ['id'], [
        //                'from_warehouse_id',
        //                'to_warehouse_id',
        //                'inventory_item_id',
        //                'quantity',
        //            ]);
        //        }
    }
}
