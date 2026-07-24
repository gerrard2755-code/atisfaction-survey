<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['username', 'email', 'password', 'fullname', 'role'];
    protected $hidden = ['password'];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
