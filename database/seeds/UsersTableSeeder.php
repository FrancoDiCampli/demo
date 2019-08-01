<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Controller',
            'email' => 'controller.programacion@gmail.com',
            'password' => bcrypt('c0ntr0ll3r'),
            'role_id' => 1,
        ]);
    }
}
