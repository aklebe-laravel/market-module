<?php

namespace Modules\Market\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Market\app\Models\User;

/**
 *
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => fake()->unique()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'shared_id'         => uniqid('js_suid_'),
            'email_verified_at' => now(),
            'password'          => '1234567',
            'remember_token'    => Str::random(10),
            //            'rating'            => fake()->randomFloat(4, 0, 100),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
