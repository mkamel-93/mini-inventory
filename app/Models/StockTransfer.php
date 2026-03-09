<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\StockTransferFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $from_warehouse_id
 * @property int $to_warehouse_id
 * @property int $inventory_item_id
 * @property int $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Warehouse                  $fromWarehouse
 * @property-read InventoryItem              $inventoryItem
 * @property-read Warehouse                  $toWarehouse
 *
 * @method static StockTransferFactory factory($count = null, $state = [])
 * @method static Builder<static>|StockTransfer newModelQuery()
 * @method static Builder<static>|StockTransfer newQuery()
 * @method static Builder<static>|StockTransfer query()
 * @method static Builder<static>|StockTransfer whereCreatedAt($value)
 * @method static Builder<static>|StockTransfer whereFromWarehouseId($value)
 * @method static Builder<static>|StockTransfer whereId($value)
 * @method static Builder<static>|StockTransfer whereInventoryItemId($value)
 * @method static Builder<static>|StockTransfer whereQuantity($value)
 * @method static Builder<static>|StockTransfer whereStatus($value)
 * @method static Builder<static>|StockTransfer whereToWarehouseId($value)
 * @method static Builder<static>|StockTransfer whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class StockTransfer extends BaseModel
{
    /** @use HasFactory<StockTransferFactory> */
    use HasFactory;

    protected $fillable = [
        'from_warehouse_id',
        'to_warehouse_id',
        'inventory_item_id',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    /**
     * Get the warehouse that the stock is transferred from.
     *
     * @return BelongsTo<Warehouse, $this>
     */
    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id')->select(['id', 'name']);
    }

    /**
     * Get the warehouse that the stock is transferred to.
     *
     * @return BelongsTo<Warehouse, $this>
     */
    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id')->select(['id', 'name']);
    }

    /**
     * Get the inventory item that is being transferred.
     *
     * @return BelongsTo<InventoryItem, $this>
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class)->select(['id', 'name', 'sku']);
    }
}
