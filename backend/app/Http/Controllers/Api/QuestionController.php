<?php

namespace App\Http\Controllers\Api;

use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QuestionController extends BaseController
{
    public function store(Request $request, Questionnaire $questionnaire)
    {
        try {
            $validated = $request->validate([
                'question' => 'required|string|max:500',
                'category' => 'required|in:content,design,usability,performance',
                'order_no' => 'required|integer|min:1',
            ]);

            $question = $questionnaire->questions()->create($validated);

            return $this->sendSuccess($question, 'Question created successfully', 201);
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error', null, 422, $e->errors());
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    public function update(Request $request, Question $question)
    {
        try {
            $validated = $request->validate([
                'question' => 'string|max:500',
                'category' => 'in:content,design,usability,performance',
                'order_no' => 'integer|min:1',
            ]);

            $question->update($validated);

            return $this->sendSuccess($question, 'Question updated successfully');
        } catch (ValidationException $e) {
            return $this->sendError('Validation Error', null, 422, $e->errors());
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    public function destroy(Question $question)
    {
        try {
            $question->delete();
            return $this->sendSuccess(null, 'Question deleted successfully', 204);
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }
}
