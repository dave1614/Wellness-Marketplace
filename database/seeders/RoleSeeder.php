<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => 1,
            'name' => 'Main Admin',
        ]);

        Role::create([
            'id' => 2,
            'name' => 'Merchant',
        ]);

        Role::create([
            'id' => 3,
            'name' => 'Shopper',
        ]);
    }
}
