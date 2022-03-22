<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        for ($i = 0; $i < 3; $i++) {
            User::factory()->create([
                'name' => 'user' . $i,
                'email' => 'user' . $i . '@szerveroldali.hu'
                // 'password' => Hash:make('jelszo')
            ]);
        }
    }
}
