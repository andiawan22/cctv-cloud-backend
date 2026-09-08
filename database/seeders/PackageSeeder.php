<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::create([
            'name' => 'Basic',
            'storage_limit_gb' => 5,
            'camera_limit' => 2,
            'is_custom' => false,
            'status' => true,
        ]);

        Package::create([
            'name' => 'Medium',
            'storage_limit_gb' => 8,
            'camera_limit' => 4,
            'is_custom' => false,
            'status' => true,
        ]);

        Package::create([
            'name' => 'Premium',
            'storage_limit_gb' => 10,
            'camera_limit' => 6,
            'is_custom' => false,
            'status' => true,
        ]);

        Package::create([
            'name' => 'Custom',
            'storage_limit_gb' => 0,
            'camera_limit' => 0,
            'is_custom' => true,
            'status' => true,
        ]);
    }
}