<?php

namespace App\Http\Controllers\Api;

use App\Models\Response;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ResponseController extends BaseController
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'questionnaire_id' => 'required|exists:questionnaires,id',
                'gender' => 'nullable|in:male,female,other',
                'age' => 'nullable|string|max:50',
                'user_type' => 'nullable|in:visitor,student,staff,teacher,alumni',
                'responses' => 'required|array|min:1',
                'responses.*.question_id' => 'required|exists:questions,id',
                'responses.*.score' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
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

            if (isset($validated['comment']) && !empty($validated['comment'])) {
                $response->suggestions()->create(['comment' => $validated['comment']]);
            }

            return $this->sendSuccess(
                ['response_id' => $response->id],
                'Response submitted successfully',
                201
            );
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error', null, 422, $e->errors());
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    public function index()
    {
        try {
            $responses = Response::with('details', 'suggestions')->get();
            return $this->sendSuccess($responses, 'Responses retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }
}
