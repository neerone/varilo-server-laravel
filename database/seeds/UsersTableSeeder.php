<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "neerone",
            'email' => 'neuroweet@gmail.com',
            'api_token' => str_random(60),
            'password' => bcrypt('sa3Keeth'),
        ]);
    }
}
