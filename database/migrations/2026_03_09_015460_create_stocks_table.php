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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Warehouse::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(InventoryItem::class)->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->unique(['warehouse_id', 'inventory_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
