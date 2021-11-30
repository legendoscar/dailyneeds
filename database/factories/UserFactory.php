<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'user_type' => $this->faker->numberBetween(1,5), 
            // 'belongs_to_store' => $this->faker->numberBetween(1,5), 
            'fname' => $this->faker->firstName, 
            'lname' => $this->faker->lastName, 
            'phone' => $this->faker->phoneNumber(), 
            'email' => $this->faker->unique()->safeEmail,
            'profile_image' => $this->faker->imageUrl(),
            'password' => $this->faker->word('$2a$12$jlLNjqeUv9.E8p0g.KAyauBSEkoTRjiy6ZaBzHUi6ExowujKDmFMC
            '), //password
        ];
    }
}
