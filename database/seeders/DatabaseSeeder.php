<?php

namespace Database\Seeders;

use App\Models\CategoryModel;
use Illuminate\Database\Seeder;
use Database\Factories\CategoriesFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        $this->call(CategoriesSeeder::class);
        $this->call(SubCategoriesSeeder::class);
        $this->call(StoresSeeder::class);
        $this->call(UserRolesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(LocationsSeeder::class);

        // CategoryModel::factory()
        //     ->count(50)
        //     ->create();


    }
}
