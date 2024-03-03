<?php

declare(strict_types=1);

namespace Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use Workbench\App\Models\Profile;
use Workbench\App\Models\Tag;
use Workbench\App\Models\User;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->count(50)
            ->create()
            ->each(function (User $user) {
                Profile::factory()->for($user, 'user')->create();
                $user->tags()->saveMany(Tag::factory()->count(5)->make());
            });
    }
}
