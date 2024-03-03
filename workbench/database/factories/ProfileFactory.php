<?php

declare(strict_types=1);

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Profile;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'postcode'   => $this->faker->postcode(),
            'address'    => $this->faker->address(),
            'latitude'   => $this->faker->latitude(),
            'longitude'  => $this->faker->longitude(),
            'color'      => $this->faker->hexColor(),
            'start_at'   => $this->faker->dateTime(),
            'end_at'     => $this->faker->dateTime(),
        ];
    }
}
