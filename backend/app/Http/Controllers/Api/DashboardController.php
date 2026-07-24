<?php

namespace App\Http\Controllers\Api;

use App\Models\Response;
use App\Models\ResponseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController
{
    public function stats()
    {
        $totalResponses = Response::count();
        $avgScore = ResponseDetail::avg('score');

        $categoryStats = DB::table('response_details')
            ->join('questions', 'response_details.question_id', '=', 'questions.id')
            ->groupBy('questions.category')
            ->selectRaw('questions.category, AVG(response_details.score) as avg_score, COUNT(*) as count')
            ->get();

        $dailyStats = Response::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'total_responses' => $totalResponses,
            'avg_score' => round($avgScore, 2),
            'category_stats' => $categoryStats,
            'daily_stats' => $dailyStats,
        ]);
    }
}
