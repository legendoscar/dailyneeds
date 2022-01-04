<?php

namespace Database\Factories;

use App\Models\LocationsModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class LocationsModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LocationsModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() 
    {
        return [
            // 'user_type' => 1,
            'name' => $this->faker->city(), 
            'desc' => $this->faker->paragraph(),
            'location_country_name' => 'Nigeria',
            'location_country_code' => '+234',
            'is_popular' => $this->faker->randomElement([0,1]), 
            'is_recommended' => $this->faker->randomElement([0,1]), 
            'is_active' => $this->faker->randomElement([0,1]), 
            // 'role_name' => $this->faker->randomElements(['admin', ''])
        ];
    }
}
