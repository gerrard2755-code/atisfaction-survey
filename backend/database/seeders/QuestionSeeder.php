<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Questionnaire;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Questions for 2024 Survey (ID: 1)
        $survey2024 = Questionnaire::find(1);
        if ($survey2024) {
            $survey2024->questions()->createMany([
                [
                    'question' => 'Is the website easy to navigate and find information?',
                    'category' => 'usability',
                    'order_no' => 1,
                ],
                [
                    'question' => 'Is the website content relevant and up-to-date?',
                    'category' => 'content',
                    'order_no' => 2,
                ],
                [
                    'question' => 'Is the website visually appealing and well-designed?',
                    'category' => 'design',
                    'order_no' => 3,
                ],
                [
                    'question' => 'Does the website load quickly and perform well?',
                    'category' => 'performance',
                    'order_no' => 4,
                ],
                [
                    'question' => 'Is the website mobile-friendly?',
                    'category' => 'design',
                    'order_no' => 5,
                ],
                [
                    'question' => 'Are you satisfied with the search functionality?',
                    'category' => 'usability',
                    'order_no' => 6,
                ],
                [
                    'question' => 'Overall, how satisfied are you with this website?',
                    'category' => 'content',
                    'order_no' => 7,
                ],
            ]);
        }

        // Questions for 2025 Survey (ID: 2)
        $survey2025 = Questionnaire::find(2);
        if ($survey2025) {
            $survey2025->questions()->createMany([
                [
                    'question' => 'How easy is it to find what you need on our website?',
                    'category' => 'usability',
                    'order_no' => 1,
                ],
                [
                    'question' => 'How would you rate the quality of our content?',
                    'category' => 'content',
                    'order_no' => 2,
                ],
                [
                    'question' => 'How satisfied are you with the design and layout?',
                    'category' => 'design',
                    'order_no' => 3,
                ],
                [
                    'question' => 'How would you rate the website performance?',
                    'category' => 'performance',
                    'order_no' => 4,
                ],
            ]);
        }

        // Questions for Service Quality Survey (ID: 3)
        $serviceSurvey = Questionnaire::find(3);
        if ($serviceSurvey) {
            $serviceSurvey->questions()->createMany([
                [
                    'question' => 'How would you rate the quality of customer support?',
                    'category' => 'content',
                    'order_no' => 1,
                ],
                [
                    'question' => 'How responsive is our support team?',
                    'category' => 'performance',
                    'order_no' => 2,
                ],
                [
                    'question' => 'Are the solutions provided helpful?',
                    'category' => 'content',
                    'order_no' => 3,
                ],
                [
                    'question' => 'Would you recommend our services to others?',
                    'category' => 'usability',
                    'order_no' => 4,
                ],
            ]);
        }
    }
}
