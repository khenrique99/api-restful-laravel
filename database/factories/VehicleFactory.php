<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'brand' => $this->faker->company(),
            'model' => $this->faker->word(),
            'year' => $this->faker->year(),
            'color' => $this->faker->colorName(),
            'price' => $this->faker->randomFloat(2, 10000, 100000),
            'user_id' => null,
        ];
    }
}
