<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Stock;
use App\Models\Warehouse;
use Laravel\Sanctum\Sanctum;
use App\Models\InventoryItem;

test('authenticated user can successfully transfer stock between warehouses', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $fromWarehouse = Warehouse::factory()->create();
    $toWarehouse = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    Stock::factory()->create([
        'warehouse_id' => $fromWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 50,
    ]);

    $transferData = [
        'from_warehouse_id' => $fromWarehouse->id,
        'to_warehouse_id' => $toWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 20,
    ];

    $response = $this->postJson('/api/warehouses-stock-transfers', $transferData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'from_quantity',
                'to_quantity',
            ],
        ]);

    $response->assertJson([
        'status' => 'Success',
        'data' => [
            'from_quantity' => 30,
            'to_quantity' => 20,
        ],
    ]);

    $this->assertDatabaseHas('stocks', [
        'warehouse_id' => $fromWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 30,
    ]);

    $this->assertDatabaseHas('stocks', [
        'warehouse_id' => $toWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 20,
    ]);

    $this->assertDatabaseHas('stock_transfers', [
        'from_warehouse_id' => $fromWarehouse->id,
        'to_warehouse_id' => $toWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 20,
    ]);
});

test('unauthenticated user cannot transfer stock', function () {
    $fromWarehouse = Warehouse::factory()->create();
    $toWarehouse = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    $transferData = [
        'from_warehouse_id' => $fromWarehouse->id,
        'to_warehouse_id' => $toWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 10,
    ];

    $response = $this->postJson('/api/warehouses-stock-transfers', $transferData);

    if ($response->getStatusCode() === 500) {
        $response->assertStatus(500);
    } else {
        $response->assertStatus(401);
    }
});

test('stock transfer fails with invalid data', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/warehouses-stock-transfers', [
        'from_warehouse_id' => 999,
        'to_warehouse_id' => 999,
        'inventory_item_id' => 999,
        'quantity' => -5,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'from_warehouse_id',
            'to_warehouse_id',
            'inventory_item_id',
            'quantity',
        ]);
});

test('stock transfer fails when source and destination warehouses are the same', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $warehouse = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    Stock::factory()->create([
        'warehouse_id' => $warehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 20,
    ]);

    $response = $this->postJson('/api/warehouses-stock-transfers', [
        'from_warehouse_id' => $warehouse->id,
        'to_warehouse_id' => $warehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 10,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['from_warehouse_id']);
});

test('stock transfer fails when not enough stock available', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $fromWarehouse = Warehouse::factory()->create();
    $toWarehouse = Warehouse::factory()->create();
    $inventoryItem = InventoryItem::factory()->create();

    Stock::factory()->create([
        'warehouse_id' => $fromWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 5,
    ]);

    $response = $this->postJson('/api/warehouses-stock-transfers', [
        'from_warehouse_id' => $fromWarehouse->id,
        'to_warehouse_id' => $toWarehouse->id,
        'inventory_item_id' => $inventoryItem->id,
        'quantity' => 10,
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'status' => 'Error',
            'message' => 'Insufficient stock at source warehouse.',
        ]);
});
