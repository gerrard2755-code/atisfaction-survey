<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserModelTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_be_created()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'fullname' => 'Test User',
            'role' => 'staff',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'username' => 'testuser',
        ]);
    }

    public function test_admin_can_be_checked()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'fullname' => 'Admin User',
            'role' => 'admin',
        ]);

        $this->assertTrue($admin->isAdmin());
    }

    public function test_staff_is_not_admin()
    {
        $staff = User::create([
            'username' => 'staff',
            'email' => 'staff@example.com',
            'password' => bcrypt('password123'),
            'fullname' => 'Staff User',
            'role' => 'staff',
        ]);

        $this->assertFalse($staff->isAdmin());
    }
}
