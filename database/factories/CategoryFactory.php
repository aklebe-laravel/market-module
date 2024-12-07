<?php

namespace Modules\Market\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Market\app\Models\Category;
use Modules\WebsiteBase\app\Models\Store;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = implode(' ', fake()->words(rand(1, 2)));

        return [
            'store_id'         => Store::with([])->get()->first()->id,
            'name'             => 'Category '.$name,
            'description'      => implode(' ', fake()->words(20)),
            'meta_description' => implode(' ', fake()->words(10)),
            'web_uri'          => app('system_base_file')->sanitize($name).'_'.uniqid('category_'),
            //            'rating'           => fake()->randomFloat(4, 0, 100),
        ];
    }
}
