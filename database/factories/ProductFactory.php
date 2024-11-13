<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cost = $this->faker->randomFloat(2, 0, 100);
        $price = $this->faker->randomFloat(2, $cost, 100);

        return [
            'code' => $this->faker->unique()->ean13(),
            'name' => $this->faker->name(),
            'cost' => $cost,
            'price' => $price,
            'category' => $this->faker->randomElement(['Charger', 'LCD', 'Board', 'Battery']),
            'init_stock' => $this->faker->numberBetween(0, 100),
            'sold' => $this->faker->numberBetween(0, 100),
            'end_stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}
