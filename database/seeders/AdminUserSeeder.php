<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users first (use delete instead of truncate to avoid FK error)
        User::query()->delete();
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@artilia.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'bio' => 'System Administrator with full access to all features',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Regular User
        User::create([
            'name' => 'John Doe',
            'email' => 'user@artilia.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'bio' => 'Regular user with standard access',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Manager User (Admin role)
        User::create([
            'name' => 'Manager Inventory',
            'email' => 'manager@artilia.com',
            'password' => Hash::make('manager123'),
            'role' => 'admin',
            'bio' => 'Inventory Manager with administrative access',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Staff User
        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@artilia.com',
            'password' => Hash::make('staff123'),
            'role' => 'user',
            'bio' => 'Warehouse staff for daily operations',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Operator User
        User::create([
            'name' => 'Operator Sistem',
            'email' => 'operator@artilia.com',
            'password' => Hash::make('operator123'),
            'role' => 'user',
            'bio' => 'System operator for inventory management',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Supervisor User (Admin role)
        User::create([
            'name' => 'Supervisor',
            'email' => 'supervisor@artilia.com',
            'password' => Hash::make('supervisor123'),
            'role' => 'admin',
            'bio' => 'Supervisor with administrative privileges',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
