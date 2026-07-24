<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Questionnaire;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        // 2024 Survey
        $survey2024 = Questionnaire::create([
            'title' => 'Website Satisfaction Survey 2024',
            'description' => 'Help us improve your experience by rating your satisfaction with our website',
            'fiscal_year' => '2024',
            'status' => 'active',
            'created_by' => 1, // Admin user
        ]);

        // 2025 Survey (Draft)
        $survey2025 = Questionnaire::create([
            'title' => 'Website Satisfaction Survey 2025',
            'description' => 'Annual satisfaction survey for the year 2025',
            'fiscal_year' => '2025',
            'status' => 'draft',
            'created_by' => 1,
        ]);

        // Service Quality Survey
        $serviceSurvey = Questionnaire::create([
            'title' => 'Service Quality Feedback',
            'description' => 'Rate the quality of our services and support',
            'fiscal_year' => '2024',
            'status' => 'active',
            'created_by' => 1,
        ]);
    }
}
