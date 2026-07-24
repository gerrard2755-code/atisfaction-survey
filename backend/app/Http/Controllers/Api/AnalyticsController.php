<?php

namespace App\Http\Controllers\Api;

use App\Models\Response;
use App\Models\ResponseDetail;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends BaseController
{
    public function overview()
    {
        try {
            $totalResponses = Response::count();
            $avgScore = ResponseDetail::avg('score') ?? 0;

            $responsesByDay = Response::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get();

            return $this->sendSuccess([
                'total_responses' => $totalResponses,
                'avg_score' => round($avgScore, 2),
                'responses_by_day' => $responsesByDay,
            ], 'Analytics overview retrieved');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }

    public function categoryAnalysis()
    {
        try {
            $categoryStats = DB::table('response_details')
                ->join('questions', 'response_details.question_id', '=', 'questions.id')
                ->groupBy('questions.category')
                ->selectRaw('questions.category, AVG(response_details.score) as avg_score, COUNT(*) as count')
                ->get();

            return $this->sendSuccess([
                'category_stats' => $categoryStats,
            ], 'Category analysis retrieved');
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage(), 500);
        }
    }
}
