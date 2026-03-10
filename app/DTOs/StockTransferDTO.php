<?php

declare(strict_types=1);

namespace App\DTOs;

class StockTransferDTO extends BaseDto
{
    public int $from_warehouse_id = 0;

    public int $to_warehouse_id = 0;

    public int $inventory_item_id = 0;

    public int $quantity = 0;
}
