<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\WarehouseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Stock|null $pivot
 * @property-read Collection<int, InventoryItem> $inventoryItems
 * @property-read int|null $inventory_items_count
 * @property-read Collection<int, StockTransfer> $stockTransfersFrom
 * @property-read int|null $stock_transfers_from_count
 * @property-read Collection<int, StockTransfer> $stockTransfersTo
 * @property-read int|null $stock_transfers_to_count
 *
 * @method static WarehouseFactory factory($count = null, $state = [])
 * @method static Builder<static>|Warehouse newModelQuery()
 * @method static Builder<static>|Warehouse newQuery()
 * @method static Builder<static>|Warehouse query()
 * @method static Builder<static>|Warehouse whereCreatedAt($value)
 * @method static Builder<static>|Warehouse whereId($value)
 * @method static Builder<static>|Warehouse whereLocation($value)
 * @method static Builder<static>|Warehouse whereName($value)
 * @method static Builder<static>|Warehouse whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Warehouse extends BaseModel
{
    /** @use HasFactory<WarehouseFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
    ];

    /**
     * Get the inventory items associated with the warehouse.
     *
     * @return BelongsToMany<InventoryItem, $this, Stock>
     */
    public function inventoryItems(): BelongsToMany
    {
        return $this->belongsToMany(InventoryItem::class, 'stocks', 'warehouse_id', 'inventory_item_id')
            ->withPivot(['id', 'quantity'])
            ->using(Stock::class)
            ->withTimestamps();
    }

    /**
     * Get the stock transfers from this warehouse.
     *
     * @return HasMany<StockTransfer, $this>
     */
    public function stockTransfersFrom(): HasMany
    {
        return $this->hasMany(StockTransfer::class, 'from_warehouse_id')
            ->select(['id', 'from_warehouse_id', 'to_warehouse_id', 'inventory_item_id', 'quantity']);
    }

    /**
     * Get the stock transfers to this warehouse.
     *
     * @return HasMany<StockTransfer, $this>
     */
    public function stockTransfersTo(): HasMany
    {
        return $this->hasMany(StockTransfer::class, 'to_warehouse_id')
            ->select(['id', 'from_warehouse_id', 'to_warehouse_id', 'inventory_item_id', 'quantity']);
    }
}
