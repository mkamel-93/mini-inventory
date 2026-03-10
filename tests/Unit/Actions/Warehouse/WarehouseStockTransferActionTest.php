<?php

declare(strict_types=1);

use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\InventoryItem;
use App\DTOs\StockTransferDTO;
use Illuminate\Http\JsonResponse;
use App\Actions\Warehouse\WarehouseStockTransferAction;
use App\Http\Requests\API\Warehouse\WarehouseStockTransferRequest;

test('it throws validation exception when transferring more stock than available', function () {
    $fromWarehouse = Warehouse::factory()->create();
    $toWarehouse = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    Stock::factory()->create([
        'warehouse_id' => $fromWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 10,
    ]);

    $dto = new StockTransferDTO([
        'from_warehouse_id' => $fromWarehouse->id,
        'to_warehouse_id' => $toWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 15,
    ]);

    $request = new WarehouseStockTransferRequest;
    $request->merge($dto->toArray());

    $mockRequest = Mockery::mock(WarehouseStockTransferRequest::class);
    $mockRequest->shouldReceive('validated')->andReturn($dto->toArray());

    $action = new WarehouseStockTransferAction;

    $response = $action($mockRequest, $action);

    expect($response)->toBeInstanceOf(JsonResponse::class);
    expect($response->getStatusCode())->toBe(422);

    $responseContent = json_decode($response->getContent(), true);
    expect($responseContent['message'])->toContain('Insufficient stock at source warehouse');

    $this->assertDatabaseHas('stocks', [
        'warehouse_id' => $fromWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 10,
    ]);
});

test('it successfully transfers stock when sufficient quantity is available', function () {
    $fromWarehouse = Warehouse::factory()->create();
    $toWarehouse = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    Stock::factory()->create([
        'warehouse_id' => $fromWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 20,
    ]);

    $dto = new StockTransferDTO([
        'from_warehouse_id' => $fromWarehouse->id,
        'to_warehouse_id' => $toWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 5,
    ]);

    $request = new WarehouseStockTransferRequest;
    $request->merge($dto->toArray());

    $mockRequest = Mockery::mock(WarehouseStockTransferRequest::class);
    $mockRequest->shouldReceive('validated')->andReturn($dto->toArray());

    $action = new WarehouseStockTransferAction;

    $response = $action($mockRequest, $action);

    expect($response)->toBeInstanceOf(JsonResponse::class);
    expect($response->getStatusCode())->toBe(201);

    $responseContent = json_decode($response->getContent(), true);
    expect($responseContent['data']['from_quantity'])->toBe(15);
    expect($responseContent['data']['to_quantity'])->toBe(5);

    $this->assertDatabaseHas('stocks', [
        'warehouse_id' => $fromWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 15,
    ]);

    $this->assertDatabaseHas('stocks', [
        'warehouse_id' => $toWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 5,
    ]);

    $this->assertDatabaseHas('stock_transfers', [
        'from_warehouse_id' => $fromWarehouse->id,
        'to_warehouse_id' => $toWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 5,
    ]);
});
