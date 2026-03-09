<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StockFactory;
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
            'quantity' => 'integer',
        ];
    }

    protected static function booted() {}

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
