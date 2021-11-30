<?php

namespace Database\Seeders;

use App\Models\SubCatModel;
use Illuminate\Database\Seeder;
// use Database\Factories\CategoriesFactory;

class SubCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        SubCatModel::factory()->count(1)->create();

    }
}
