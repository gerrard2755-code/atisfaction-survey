<?php

namespace App\Http\Controllers\Api;

use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QuestionnaireController extends BaseController
{
    public function index()
    {
        try {
            $questionnaires = Questionnaire::where('status', 'active')
                ->with('questions')
                ->get();

            return $this->sendSuccess($questionnaires, 'Questionnaires retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    public function show(Questionnaire $questionnaire)
    {
        try {
            if ($questionnaire->status !== 'active') {
                return $this->sendError('Not Found', 'This questionnaire is not available', 404);
            }

            return $this->sendSuccess(
                $questionnaire->load('questions'),
                'Questionnaire retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'fiscal_year' => 'required|string|max:10',
            ]);

            $questionnaire = Questionnaire::create([
                ...$validated,
                'created_by' => auth()->id() ?? 1,
                'status' => 'draft',
            ]);

            return $this->sendSuccess($questionnaire, 'Questionnaire created successfully', 201);
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error', null, 422, $e->errors());
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        try {
            $validated = $request->validate([
                'title' => 'string|max:255',
                'description' => 'nullable|string|max:1000',
                'status' => 'in:draft,active,inactive',
            ]);

            $questionnaire->update($validated);

            return $this->sendSuccess($questionnaire, 'Questionnaire updated successfully');
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error', null, 422, $e->errors());
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    public function destroy(Questionnaire $questionnaire)
    {
        try {
            $questionnaire->delete();
            return $this->sendSuccess(null, 'Questionnaire deleted successfully', 204);
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }
}
