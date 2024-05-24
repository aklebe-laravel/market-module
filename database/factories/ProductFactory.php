<?php

namespace Modules\Market\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Market\app\Models\Product;
use Modules\SystemBase\app\Services\FileService;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Market\app\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = implode(' ', fake()->words(rand(1, 4)));

        return [
            'name'              => 'Product '.$name,
            'short_description' => implode(' ', fake()->words(10)),
            'description'       => implode(' ', fake()->words(20)),
            'meta_description'  => implode(' ', fake()->words(10)),
            'web_uri'           => FileService::sanitize($name).'_'.uniqid('product_'),
        ];
    }

    /**
     * Dynamic called method by type xxx into fakeAttributeType_xxx()
     *
     * @return string
     */
    public static function fakeAttributeType_string(): string
    {
        return implode(' ', fake()->words(rand(1, 4)));
    }

    /**
     * Dynamic called method by type xxx into fakeAttributeType_xxx()
     *
     * @return string
     */
    public static function fakeAttributeType_text(): string
    {
        return implode(' ', fake()->words(rand(5, 15)));
    }

    /**
     * Dynamic called method by type xxx into fakeAttributeType_xxx()
     *
     * @return int
     */
    public static function fakeAttributeType_integer(): int
    {
        return rand(1, 10);
    }

    /**
     * Dynamic called method by type xxx into fakeAttributeType_xxx()
     *
     * @return float
     */
    public static function fakeAttributeType_double(): float
    {
        return (float) rand(0, 10000) / 100.0;
    }

}
