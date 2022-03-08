<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
        $user = new User;
        $user->first_name = "Super";
        $user->last_name = "Admin";
        $user->email = "admin@mail.com";
        $user->password = Hash::make('Admin@123');
        // $user->uid = '0';
        $user->save();

        $user->roles()->attach(1);
    }
}
