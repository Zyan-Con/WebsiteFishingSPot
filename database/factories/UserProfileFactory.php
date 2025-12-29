<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'bio' => fake()->paragraph(),
            'favorite_spot' => fake()->city() . ' Beach',
            'fishing_experience' => fake()->randomElement(['beginner', 'intermediate', 'advanced', 'expert']),
        ];
    }
}