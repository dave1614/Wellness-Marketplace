<?php

namespace Database\Seeders;

use App\Functions\UsefulFunctions;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $functions = new UsefulFunctions();

        $date = date("j M Y");
        $time = date("h:i:sa");

        User::create([
            'name' => 'Main Admin',
            'user_name' => 'admin',
            'email' => 'main_admin@gmail.com',
            'phone' => '08174622455',
            'country_id' => 151,
            'created_date' => $date,
            'created_time' => $time,
            'password' => Hash::make('Dave1614..'),
            'remember_token' => Str::random(10),
            'role_id' => 1
        ]);

        $functions->populateDbWithUsersAndBusinesses();
    }
}
