<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',

            'username' => 'admin',

            'email' => 'admin@cloudcctv.com',

            'password' => Hash::make('admin123'),

            'role' => 'admin',

            'status' => 'active',

            // gunakan paket Basic sebagai default
            'package_id' => 1,
        ]);
    }
}