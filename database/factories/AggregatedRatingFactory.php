<?php

namespace Modules\Market\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Market\app\Models\AggregatedRating;
use Modules\Market\app\Models\Category;

/**
 * @extends Factory<Category>
 */
class AggregatedRatingFactory extends Factory
{
    protected $model = AggregatedRating::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value' => fake()->randomFloat(4, 20, 100),
        ];
    }
}
