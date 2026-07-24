<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = ['response_id', 'comment'];
    protected $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function response()
    {
        return $this->belongsTo(Response::class);
    }
}
