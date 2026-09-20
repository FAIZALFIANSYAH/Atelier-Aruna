<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'member', 'email' => 'member@test.com', 'password' => Hash::make('password'), 'role_id' => 1],

            ['name' => 'admin', 'email' => 'admin@test.com', 'password' => Hash::make('password'), 'role_id' => 2],

            ['name' => 'cashier', 'email' => 'cashier@test.com', 'password' => Hash::make('password'), 'role_id' => 3],
        ];

        User::insert($users);
    }
}
