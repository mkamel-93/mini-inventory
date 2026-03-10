<?php

declare(strict_types=1);

namespace App\Actions\Inventory;

use App\Facades\ToggleCache;
use App\Models\InventoryItem;
use App\DTOs\InventoryFilterDTO;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\InventoryItemResource;
use App\Http\Requests\API\Inventory\InventoryListRequest;

class InventoryListAction
{
    public function __invoke(InventoryListRequest $request): JsonResponse
    {
        $dto = InventoryFilterDTO::fromRequest($request);

        $cacheKey = $dto->toCacheKey('warehouse_inv_list');

        $data = ToggleCache::remember($cacheKey, function () use ($dto) {
            return InventoryItem::query()
                ->when($dto->warehouse_id, function ($query) use ($dto) {
                    $query->whereHas('stocks', function ($stockQuery) use ($dto) {
                        $stockQuery->where('warehouse_id', $dto->warehouse_id);
                    });
                })
                ->when($dto->name, fn ($q) => $q->where('name', 'like', "%{$dto->name}%"))
                ->when($dto->min_price, fn ($q) => $q->where('price', '>=', $dto->min_price))
                ->when($dto->max_price, fn ($q) => $q->where('price', '<=', $dto->max_price))
                ->paginate(perPage: $dto->per_page, page: $dto->page);
        });

        return InventoryItemResource::collection($data)->response();
    }
}
