<?php

namespace Database\Seeders;

use App\Models\LocationsModel;
use Illuminate\Database\Seeder;
// use Database\Factories\CategoriesFactory;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        LocationsModel::factory()->count(10)->create();

    } 
}
