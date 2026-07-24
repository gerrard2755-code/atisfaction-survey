<?php

namespace App\Http\Controllers\Api;

use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnaireController
{
    public function index()
    {
        return response()->json(Questionnaire::where('status', 'active')->with('questions')->get());
    }

    public function show(Questionnaire $questionnaire)
    {
        return response()->json($questionnaire->load('questions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'fiscal_year' => 'required|string',
        ]);

        $questionnaire = Questionnaire::create([
            ...$validated,
            'created_by' => auth()->id(),
            'status' => 'draft',
        ]);

        return response()->json($questionnaire, 201);
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'in:draft,active,inactive',
        ]);

        $questionnaire->update($validated);
        return response()->json($questionnaire);
    }

    public function destroy(Questionnaire $questionnaire)
    {
        $questionnaire->delete();
        return response()->json(null, 204);
    }
}
