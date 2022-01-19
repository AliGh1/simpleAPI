<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
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
        $category = Category::factory(2)->create()->each(function ($category){
            Category::factory()->count(2)->withParent($category->id)->create();
        });

         User::factory(5)->has(Post::factory(3)->hasAttached($category))->create([
             'is_admin' => true
         ]);
    }
}
