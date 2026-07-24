<?php

namespace App\Http\Controllers\Api;

use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionController
{
    public function store(Request $request, Questionnaire $questionnaire)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'category' => 'required|in:content,design,usability,performance',
            'order_no' => 'required|integer',
        ]);

        $question = $questionnaire->questions()->create($validated);
        return response()->json($question, 201);
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'question' => 'string',
            'category' => 'in:content,design,usability,performance',
            'order_no' => 'integer',
        ]);

        $question->update($validated);
        return response()->json($question);
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json(null, 204);
    }
}
