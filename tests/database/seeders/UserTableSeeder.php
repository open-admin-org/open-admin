<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Tests\Models\Profile;
use Tests\Models\Tag;
use Tests\Models\User;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 users with profiles and tags
        User::factory()
            ->count(10)
            ->create()
            ->each(function ($user) {
                // You can define ProfileFactory and TagFactory similarly if needed
                Profile::factory()->create(['user_id' => $user->id]);
                $user->tags()->saveMany(Tag::factory()->count(3)->make());
            });
    }
}
