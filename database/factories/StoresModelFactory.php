<?php

namespace Database\Factories;

use App\Models\StoresModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class StoresModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StoresModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'store_name' => $this->faker->unique()->company(),
            'store_address' => $this->faker->address(),
            'store_phone' => $this->faker->unique()->phoneNumber,
            'store_email' => $this->faker->unique()->safeEmail,
            'store_image' => $this->faker->imageUrl(),
            'store_about' => $this->faker->paragraph(),
            'store_password' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['active', 'suspended', 'deactivated']),
            'store_cat_id' => $this->faker->randomNumber(),

        ];
    }
}
