<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\InventoryItemFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property numeric $price
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, StockTransfer> $stockTransfers
 * @property-read int|null                       $stock_transfers_count
 * @property-read Collection<int, Stock>         $stocks
 * @property-read int|null                       $stocks_count
 * @property Stock|null $pivot
 *
 * @method static InventoryItemFactory factory($count = null, $state = [])
 * @method static Builder<static>|InventoryItem newModelQuery()
 * @method static Builder<static>|InventoryItem newQuery()
 * @method static Builder<static>|InventoryItem priceRange(?mixed $min, ?mixed $max)
 * @method static Builder<static>|InventoryItem query()
 * @method static Builder<static>|InventoryItem whereCreatedAt($value)
 * @method static Builder<static>|InventoryItem whereDescription($value)
 * @method static Builder<static>|InventoryItem whereId($value)
 * @method static Builder<static>|InventoryItem whereName($value)
 * @method static Builder<static>|InventoryItem wherePrice($value)
 * @method static Builder<static>|InventoryItem whereSku($value)
 * @method static Builder<static>|InventoryItem whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class InventoryItem extends BaseModel
{
    /** @use HasFactory<InventoryItemFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    /**
     * @param  Builder<InventoryItem>  $query
     * @return Builder<InventoryItem>
     */
    public function scopePriceRange(Builder $query, mixed $min, mixed $max): Builder
    {
        if (! is_null($min) && $min !== '') {
            $query->where('price', '>=', (float) $min);
        }

        if (! is_null($max) && $max !== '') {
            $query->where('price', '<=', (float) $max);
        }

        return $query;
    }

    /**
     * Get the stocks for the inventory item.
     *
     * @return HasMany<Stock, $this>
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Get the stock transfers for the inventory item.
     *
     * @return HasMany<StockTransfer, $this>
     */
    public function stockTransfers(): HasMany
    {
        return $this->hasMany(StockTransfer::class);
    }
}
