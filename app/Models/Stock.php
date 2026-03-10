<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StockFactory;
use App\Events\LowStockDetectedEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $warehouse_id
 * @property int $inventory_item_id
 * @property int $quantity
 * @property-read InventoryItem|null $inventoryItem
 * @property-read Warehouse|null     $warehouse
 *
 * @method static StockFactory factory($count = null, $state = [])
 * @method static Builder<static>|Stock newModelQuery()
 * @method static Builder<static>|Stock newQuery()
 * @method static Builder<static>|Stock query()
 *
 * @mixin \Eloquent
 */
class Stock extends Pivot
{
    /** @use HasFactory<StockFactory> */
    use HasFactory;

    protected $table = 'stocks';

    public $timestamps = true;

    protected $fillable = [
        'warehouse_id',
        'inventory_item_id',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'warehouse_id' => 'integer',
            'inventory_item_id' => 'integer',
            'quantity' => 'integer',
        ];
    }

    protected static function booted()
    {
        static::saved(function (Stock $stock) {
            $defaultMinQuantity = 5;
            $previousQuantity = $stock->getOriginal('quantity');
            $newQuantity = $stock->quantity;

            if ($newQuantity < $defaultMinQuantity && $previousQuantity >= $defaultMinQuantity) {
                $stock->loadMissing(['inventoryItem', 'warehouse']);

                if ($stock->warehouse && $stock->inventoryItem) {
                    event(new LowStockDetectedEvent($stock->warehouse, $stock->inventoryItem));

                    logger()->info('LowStockDetectedEvent dispatched successfully', [
                        'warehouse_name' => $stock->warehouse->name,
                        'inventory_item_name' => $stock->inventoryItem->name,
                        'current_quantity' => $newQuantity,
                    ]);
                } else {
                    logger()->warning("Low stock detected, but relations are missing for Stock ID: {$stock->id}");
                }
            }
        });
    }

    /**
     * Get the warehouse that owns the stock.
     *
     * @return BelongsTo<Warehouse, $this>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class)->select(['id', 'name']);
    }

    /**
     * Get the inventory item that owns the stock.
     *
     * @return BelongsTo<InventoryItem, $this>
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class)->select(['id', 'name']);
    }
}
