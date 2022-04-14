<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        /* Delete data from DB */
        DB::table('users')->truncate();
        DB::table('categories')->truncate();
        DB::table('posts')->truncate();

        /* Users */
        // Admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@szerveroldali.hu',
            'is_admin' => true,
            'password' => bcrypt('password')
        ]);

        // Users
        for($i = 1; $i <= rand(3, 6); $i++) {
            User::factory()->create([
                'name' => 'User' . $i,
                'email' => 'user' . $i . '@szerveroldali.hu',
                'is_admin' => false,
                'password' => bcrypt('password')
            ]);
        }

        /* Categories */
        Category::factory(rand(10, 15))->create();

        /* Posts */
        $users = User::all();
        $users_count = $users->count();

        $categories = Category::all();
        $categories_count = $categories->count();

        for($i = 1; $i <= rand(15, 20); $i++) {
            // attach User
            $rand_user = $users->random();

            $post = Post::factory()->create([
                'author_id' => $rand_user['id']
            ]);
            $post->author()->associate($rand_user);

            // attach Categories
            $category_ids = $categories->random(rand(1, $categories_count))->pluck('id')->toArray();
            $post ->categories()->attach($category_ids);

            // save
            $post->save();
        }
    }
}
