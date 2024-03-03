<?php

declare(strict_types=1);

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Workbench\App\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username' => $this->faker->userName(),
            'email'    => $this->faker->email(),
            'mobile'   => $this->faker->phoneNumber(),
            'avatar'   => $this->faker->imageUrl(),
            'password' => Hash::make('123456'),
            'data'     => [
                'json' => [
                    'field' => $this->faker->numberBetween(1, 50),
                ],
            ],
        ];
    }
}
