<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['questionnaire_id', 'question', 'category', 'order_no'];
    protected $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];

    const CATEGORY_CONTENT = 'content';
    const CATEGORY_DESIGN = 'design';
    const CATEGORY_USABILITY = 'usability';
    const CATEGORY_PERFORMANCE = 'performance';

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function responseDetails()
    {
        return $this->hasMany(ResponseDetail::class);
    }
}
