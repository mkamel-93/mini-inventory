<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\InventoryItem;

/**
 * @extends BaseFactory<InventoryItem>
 */
class InventoryItemFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Inventory-Item-'.fake()->unique()->numerify('#'),
            'sku' => strtoupper(fake()->bothify('??-####-??')),
            'price' => fake()->randomFloat(2, 10, 1000),
            'description' => fake()->realText(),
        ];
    }
}
