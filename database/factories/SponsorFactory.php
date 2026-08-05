<?php

namespace Database\Factories;

use App\Models\Sponsor;
use Illuminate\Database\Eloquent\Factories\Factory;

class SponsorFactory extends Factory
{
    protected $model = Sponsor::class;

    public function definition(): array
    {
        return [
            'name'     => $this->faker->name(),
            'type'     => 'individual',
            'phone'    => $this->faker->phoneNumber(),
            'email'    => $this->faker->safeEmail(),
            'address'  => $this->faker->address(),
            'tax_code' => null,
            'notes'    => null,
        ];
    }

    public function individual(): static
    {
        return $this->state(fn () => ['type' => 'individual', 'tax_code' => null]);
    }

    public function organization(): static
    {
        return $this->state(fn () => [
            'type'     => 'organization',
            'name'     => $this->faker->company(),
            'tax_code' => $this->faker->numerify('0#########'),
        ]);
    }
}