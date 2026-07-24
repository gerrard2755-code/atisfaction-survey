<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'fullname' => 'Administrator',
            'role' => 'admin',
        ]);

        // Staff users
        User::create([
            'username' => 'staff1',
            'email' => 'staff1@example.com',
            'password' => Hash::make('staff123'),
            'fullname' => 'Staff Member 1',
            'role' => 'staff',
        ]);

        User::create([
            'username' => 'staff2',
            'email' => 'staff2@example.com',
            'password' => Hash::make('staff123'),
            'fullname' => 'Staff Member 2',
            'role' => 'staff',
        ]);

        // Manager user
        User::create([
            'username' => 'manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('manager123'),
            'fullname' => 'Manager User',
            'role' => 'manager',
        ]);
    }
}
