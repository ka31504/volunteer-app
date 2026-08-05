<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'           => $this->faker->sentence(4),
            'description'    => $this->faker->paragraph(),
            'target_amount'  => $this->faker->numberBetween(5_000_000, 50_000_000),
            'current_amount' => 0,
            'status'         => $this->faker->randomElement(['planning', 'ongoing', 'completed']),
            'start_date'     => now()->format('Y-m-d'),
            'end_date'       => now()->addMonths(3)->format('Y-m-d'),
        ];
    }
}