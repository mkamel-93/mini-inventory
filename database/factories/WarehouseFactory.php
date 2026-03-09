<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Warehouse;

/**
 * @extends BaseFactory<Warehouse>
 */
class WarehouseFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Warehouse-'.fake()->unique()->numerify('#'),
            'location' => fake()->city().', '.fake()->country(),
        ];
    }
}
