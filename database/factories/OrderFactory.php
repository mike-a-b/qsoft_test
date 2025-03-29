<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
//            'user_id' => fake()->numberBetween(1, 10),
            'name' => fake()->unique()->firstName(),
            'status' => 'Active',
            'email' => fake()->unique()->safeEmail(),
            'message' => fake()->text(255),
            'comment' => null,
        ];
    }
}
