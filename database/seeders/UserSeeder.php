<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Michael Chacón',
            'email' => 'michael@gmail.com',
            'password' => bcrypt('1234567')
        ]);

        $user->assignRole('admin');


        User::factory(99)->create();
    }
}
