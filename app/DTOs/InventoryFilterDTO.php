<?php

declare(strict_types=1);

namespace App\DTOs;

class InventoryFilterDTO extends BaseDto
{
    public int $warehouse_id = 0;

    public ?string $name = null;

    public ?string $min_price = null;

    public ?string $max_price = null;

    public int $per_page = 15;

    public int $page = 1;
}
