<?php

namespace Database\Factories;

use App\Models\SubCatModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class SubCatModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubCatModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sub_cat_title' => $this->faker->unique()->firstName(),
            'sub_cat_desc' => $this->faker->paragraph(),
            'cat_type' => $this->faker->numberBetween(1,2),
            'cat_id' => $this->faker->randomNumber(),
            'sub_cat_image' => $this->faker->imageUrl(),

        ];
    }
}
