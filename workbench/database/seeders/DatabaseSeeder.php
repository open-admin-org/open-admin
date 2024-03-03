<?php

declare(strict_types=1);

namespace Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use OpenAdmin\Admin\Auth\Database\AdminTablesSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $userModel = config('admin.database.users_model');

        if ($userModel::count() === 0) {
            $this->call(AdminTablesSeeder::class);
        }

        $this->call(UserTableSeeder::class);
    }
}
