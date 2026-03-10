<?php

declare(strict_types=1);

namespace App\Actions\Warehouse;

use App\Models\Warehouse;
use App\Facades\ToggleCache;
use App\DTOs\WarehouseFilterDTO;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\WarehouseResource;
use App\Http\Requests\API\Warehouse\WarehouseListRequest;

class WarehouseListAction
{
    public function __invoke(WarehouseListRequest $request): JsonResponse
    {
        $dto = WarehouseFilterDTO::fromRequest($request);
        $cacheKey = $dto->toCacheKey('warehouses');

        $data = ToggleCache::remember($cacheKey, function () use ($dto) {
            return Warehouse::query()
                ->when($dto->name, fn ($q) => $q->where('name', 'like', "%{$dto->name}%"))
                ->paginate(perPage: $dto->per_page, page: $dto->page);
        });

        return WarehouseResource::collection($data)->response();
    }
}
