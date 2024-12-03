<?php

namespace Modules\Market\database\seeders;

use Modules\Market\app\Models\Category;
use Modules\SystemBase\database\seeders\BaseModelSeeder;

class CategorySeeder extends BaseModelSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        parent::run();

        $this->TryCreateFactories(Category::class, config('seeders.categories.count', 10));
    }
}
