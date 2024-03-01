<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'people_id' => $this->faker->numberBetween(1, 10),
            'contact' => $this->faker->phoneNumber(),
            'email_verified_at' => now(),
            'password' => password_hash($this->faker->password(), PASSWORD_DEFAULT),
            'remember_token' => Str::random(10),
            'is_active' => $this->faker->boolean(),
            'picture' => $this->faker->url(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
