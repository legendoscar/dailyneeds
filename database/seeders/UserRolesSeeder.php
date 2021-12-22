<?php

namespace Database\Seeders;

use App\Models\UserRolesModel;
use Illuminate\Database\Seeder;
// use Database\Factories\CategoriesFactory;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        UserRolesModel::factory()->count(1)->create();

    } 
}
