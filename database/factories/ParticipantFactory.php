<?php

namespace Database\Factories;

use App\Models\Participant;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipantFactory extends Factory
{
    protected $model = Participant::class;

    public function definition(): array
    {
        $joinedAt = $this->faker->dateTimeBetween('-2 years', '-1 month');

        return [
            'project_id'        => Project::factory(),
            'full_name'         => $this->faker->name(),
            'phone'             => $this->faker->numerify('0#########'),
            'email'             => $this->faker->unique()->safeEmail(),
            'birth_date'        => $this->faker->dateTimeBetween('-50 years', '-18 years'),
            'gender'            => $this->faker->randomElement(['male', 'female', 'other']),
            'address'           => $this->faker->address(),
            'joined_at'         => $joinedAt,
            'ended_at'          => $this->faker->optional(0.3)->dateTimeBetween($joinedAt, 'now'),
            'hours_contributed' => $this->faker->numberBetween(0, 500),
            'role'              => $this->faker->randomElement(['volunteer', 'team_lead', 'coordinator']),
            'status'            => $this->faker->randomElement(['active', 'active', 'active', 'inactive', 'pending']),
            'notes'             => $this->faker->optional(0.4)->sentence(),
        ];
    }

    public function active(): static
    {
        return $this->state(['status' => 'active', 'ended_at' => null]);
    }

    public function teamLead(): static
    {
        return $this->state(['role' => 'team_lead']);
    }
}