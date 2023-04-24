<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;
use Symfony\Component\Uid\Ulid;

class StockFactory extends Factory
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
            'stock' => fake()->randomNumber(nbDigits: 2),
            'city_id' => Ulid::generate(),
        ];
    }

    public function withCity(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'city_id' => City::factory()->create()->id,
            ];
        });
    }
}
