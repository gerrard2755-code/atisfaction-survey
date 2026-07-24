<?php

namespace App\Http\Controllers\Api;

use App\Models\Response;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class ResponseController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'gender' => 'nullable|in:male,female,other',
            'age' => 'nullable|string',
            'user_type' => 'nullable|in:visitor,student,staff,teacher,alumni',
            'responses' => 'required|array',
            'responses.*.question_id' => 'required|exists:questions,id',
            'responses.*.score' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $response = Response::create([
            'questionnaire_id' => $validated['questionnaire_id'],
            'gender' => $validated['gender'] ?? null,
            'age' => $validated['age'] ?? null,
            'user_type' => $validated['user_type'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        foreach ($validated['responses'] as $detail) {
            $response->details()->create($detail);
        }

        if (isset($validated['comment'])) {
            $response->suggestions()->create(['comment' => $validated['comment']]);
        }

        return response()->json(['message' => 'Response submitted successfully'], 201);
    }

    public function index()
    {
        return response()->json(Response::with('details', 'suggestions')->get());
    }
}
