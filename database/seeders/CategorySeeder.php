<?php

namespace Modules\Market\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Market\app\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->count(10)->create();
    }
}
