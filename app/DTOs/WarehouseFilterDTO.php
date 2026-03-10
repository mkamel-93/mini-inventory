<?php

declare(strict_types=1);

namespace App\DTOs;

class WarehouseFilterDTO extends BaseDto
{
    public ?string $name = null;

    public int $per_page = 15;

    public int $page = 1;
}
