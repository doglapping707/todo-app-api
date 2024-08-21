<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('123456789'),
            ],
            [
                'name' => 'yamada',
                'email' => 'yamada@example.com',
                'password' => Hash::make('123456789'),
            ],
            [
                'name' => 'tanaka',
                'email' => 'tanaka@example.com',
                'password' => Hash::make('123456789'),
            ],
        ]);
    }
}
