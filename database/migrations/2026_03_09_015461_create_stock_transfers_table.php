<?php

declare(strict_types=1);

use App\Models\Warehouse;
use App\Models\InventoryItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Warehouse::class, 'from_warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Warehouse::class, 'to_warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(InventoryItem::class, 'inventory_item_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
