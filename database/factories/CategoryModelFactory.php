<?php

namespace Database\Factories;

use App\Models\CategoryModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class CategoryModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CategoryModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cat_title' => $this->faker->randomElement(['Supermarket', 'Restaurant', 'Pharmacy', 'Fast food',
            'Snack Shop', 'Smoke Accessories Shop']), // for store categories
            'cat_desc' => $this->faker->paragraph(),
            'cat_type' => 1,  #1=>store  # 2=>product 3=>users 
            'cat_image' => $this->faker->imageUrl(), 

        ];
    }
}
