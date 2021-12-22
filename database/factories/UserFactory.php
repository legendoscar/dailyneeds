<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_role' => $this->faker->numberBetween(1,1), 
            'fname' => 'Admin', 
            'lname' => 'Super', 
            'phone' => '+234-806-470-9889', 
            'email' => 'admin@dailyneeds.com.ng',
            'profile_image' => $this->faker->imageUrl(),
            'password' => Hash::make('password'),
        ];
    }
}
