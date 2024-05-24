<?php

namespace Modules\Market\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Market\app\Models\Category;
use Modules\SystemBase\app\Services\FileService;
use Modules\WebsiteBase\app\Models\Store;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Market\app\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = implode(' ', fake()->words(rand(1, 2)));

        return [
            'store_id'         => Store::with([])->get()->first()->id,
            'name'             => 'Category '.$name,
            'description'      => implode(' ', fake()->words(20)),
            'meta_description' => implode(' ', fake()->words(10)),
            'web_uri'          => FileService::sanitize($name).'_'.uniqid('category_'),
            //            'rating'           => fake()->randomFloat(4, 0, 100),
        ];
    }
}
