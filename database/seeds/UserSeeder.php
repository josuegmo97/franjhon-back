<?php

use App\User;
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
        if(count(User::all() )== 0)
        {
            User::create([
                'name'     => 'josue',
                'email'    => 'admin@mail.com',
                'password' => bcrypt('admin0506'),
            ]);

            User::create([
                'name'     => 'humberto',
                'email'    => 'comercializadora_franjhon2013@hotmail.com',
                'password' => bcrypt('humberto88'),
            ]);

        }
    }
}
