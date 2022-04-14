<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(PostSeeder::class);

        $categories = Category::all();
        $categories_count = $categories->count();

        $users = User::all();
        $users_count = $users->count();

        Post::all()->each(function ($post) use (&$categories, &$categories_count, &$users, &$users_count) {
            // attach categories to a post
            $category_ids = $categories->random(rand(1, $categories_count))->pluck('id')->toArray();
            $post->categories()->attach($category_ids);

            // attach author to a post
            if ($users_count > 0) {
                $post->user()->associate($users->random());
                $post->save();
            }
        });
    }
}
