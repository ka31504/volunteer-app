// database/factories/DonationFactory.php
<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id'     => Project::factory(),
            'donor_name'     => $this->faker->name(),
            'donor_phone'    => $this->faker->phoneNumber(),
            'type'           => 'money',
            'amount'         => $this->faker->numberBetween(100_000, 5_000_000),
            'payment_method' => $this->faker->randomElement(['cash', 'transfer', 'other']),
            'donated_at'     => $this->faker->dateThisYear(),
            'note'           => $this->faker->optional()->sentence(),
        ];
    }

    public function goods(): static
    {
        return $this->state([
            'type'              => 'goods',
            'amount'            => null,
            'goods_description' => $this->faker->word(),
            'goods_quantity'    => $this->faker->numberBetween(1, 100),
            'payment_method'    => 'other',
        ]);
    }
}