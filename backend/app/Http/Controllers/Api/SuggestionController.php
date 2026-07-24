<?php

namespace App\Http\Controllers\Api;

use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $suggestions = Suggestion::with('response')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return $this->sendSuccess($suggestions, 'Suggestions retrieved');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    public function show(Suggestion $suggestion)
    {
        try {
            return $this->sendSuccess(
                $suggestion->load('response'),
                'Suggestion retrieved'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    public function delete(Suggestion $suggestion)
    {
        try {
            $suggestion->delete();
            return $this->sendSuccess(null, 'Suggestion deleted', 204);
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }
}
