<?php

namespace Database\Factories;

use App\Models\UserRolesModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserRolesModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserRolesModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() 
    {
        return [
            'user_type' => $this->faker->numberBetween(1,3), 
            // 'user_type' => 1,
            // 'role_name' => 'admin',
            'role_name' => $this->faker->randomElement(['admin', 'customer', 'driver'])
        ];
    }
}
