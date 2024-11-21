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
            'cost' => $cost * 1000,
            'price' => $price * 1000,
            'category' => $this->faker->randomElement(['Charger', 'LCD', 'Board', 'Battery']),
            'init_stock' => 0,
            'sold' => 0,
            'end_stock' => 0,
        ];
    }
}
