<?php

namespace Modules\Market\database\seeders;

use Modules\Market\app\Models\User;
use Modules\SystemBase\database\seeders\BaseModelSeeder;

class UserSeeder extends BaseModelSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        parent::run();

        // @todo: add user groups/resources
        $this->TryCreateFactories(User::class, config('seeders.users.count', 10));
    }
}
