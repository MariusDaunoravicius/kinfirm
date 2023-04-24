<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => fake()->word,
            'description' => fake()->word,
            'size' => fake()->word,
            'photo' => fake()->imageUrl,
            'product_updated_at' => Carbon::now(),
        ];
    }
}
