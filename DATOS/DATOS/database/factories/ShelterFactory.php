<?php

namespace Database\Factories;

use App\Models\Shelter;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShelterFactory extends Factory
{
    protected $model = Shelter::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Refugio',
            'city' => fake()->city(),
            'phone' => fake()->phoneNumber(),
            'description' => fake()->paragraph(),
        ];
    }
}
