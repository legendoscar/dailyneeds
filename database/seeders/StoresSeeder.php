<?php

namespace Database\Seeders;

use App\Models\StoresModel;
use Illuminate\Database\Seeder;
// use Database\Factories\CategoriesFactory;

class StoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        StoresModel::factory()->count(1)->create();

    }
}
