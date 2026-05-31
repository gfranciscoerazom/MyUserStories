<?php

namespace Database\Factories;

use App\Enums\UserStorieStatus;
use App\Models\Project;
use App\Models\User;
use App\Models\UserStorie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserStorie>
 */
class UserStorieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'story' => fake()->paragraph(),
            'status' => fake()->randomElement(UserStorieStatus::values()),
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
        ];
    }
}
