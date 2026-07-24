<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['username', 'email', 'password', 'fullname', 'role'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF = 'staff';

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
}
