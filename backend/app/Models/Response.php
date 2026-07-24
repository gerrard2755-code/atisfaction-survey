<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = ['questionnaire_id', 'gender', 'age', 'user_type', 'ip_address', 'user_agent'];
    protected $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];

    const USER_TYPE_VISITOR = 'visitor';
    const USER_TYPE_STUDENT = 'student';
    const USER_TYPE_STAFF = 'staff';
    const USER_TYPE_TEACHER = 'teacher';
    const USER_TYPE_ALUMNI = 'alumni';

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function details()
    {
        return $this->hasMany(ResponseDetail::class);
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }
}
