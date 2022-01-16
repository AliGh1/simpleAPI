<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory(5)->hasPosts(2)->create();
         Category::factory(2)->create()->each(function ($category){
             Category::factory()->count(2)->withParent($category->id)->create();
         });

    }
}
