<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Actions\Inventory\InventoryListAction;
use App\Actions\Warehouse\WarehouseListAction;
use App\Actions\Warehouse\WarehouseShowAction;
use App\Http\Controllers\API\ProfileController;
use App\Actions\Warehouse\WarehouseStockTransferAction;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/logout', [ProfileController::class, 'logout']);

    Route::get('/warehouses-inventory-item-list', InventoryListAction::class);
    Route::get('/warehouses', WarehouseListAction::class);
    Route::get('/warehouses/{warehouse}/inventory', WarehouseShowAction::class);
    Route::post('/warehouses-stock-transfers', WarehouseStockTransferAction::class);
});
