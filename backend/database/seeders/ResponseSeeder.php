<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Response;
use App\Models\ResponseDetail;
use App\Models\Suggestion;
use Faker\Factory as Faker;

class ResponseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $userTypes = ['visitor', 'student', 'staff', 'teacher', 'alumni'];
        $genders = ['male', 'female', 'other'];
        $ages = ['under-18', '18-25', '26-35', '36-50', 'over-50'];
        $suggestions = [
            'Great website! Very informative.',
            'Could improve the navigation menu.',
            'Excellent user experience.',
            'Please add more search filters.',
            'Website is very slow sometimes.',
            'Love the design and layout!',
            'Need better documentation.',
            'Mobile version needs improvement.',
            'Very satisfied with the service.',
            'Could use more interactive features.',
        ];

        // Create 50 responses for 2024 Survey
        for ($i = 0; $i < 50; $i++) {
            $response = Response::create([
                'questionnaire_id' => 1,
                'gender' => $faker->randomElement($genders),
                'age' => $faker->randomElement($ages),
                'user_type' => $faker->randomElement($userTypes),
                'ip_address' => $faker->ipv4(),
                'user_agent' => $faker->userAgent(),
            ]);

            // Add response details (scores for each question)
            for ($qId = 1; $qId <= 7; $qId++) {
                ResponseDetail::create([
                    'response_id' => $response->id,
                    'question_id' => $qId,
                    'score' => $faker->numberBetween(1, 5),
                ]);
            }

            // Add suggestion (60% chance)
            if ($faker->boolean(60)) {
                Suggestion::create([
                    'response_id' => $response->id,
                    'comment' => $faker->randomElement($suggestions),
                ]);
            }
        }

        // Create 30 responses for Service Quality Survey
        for ($i = 0; $i < 30; $i++) {
            $response = Response::create([
                'questionnaire_id' => 3,
                'gender' => $faker->randomElement($genders),
                'age' => $faker->randomElement($ages),
                'user_type' => $faker->randomElement($userTypes),
                'ip_address' => $faker->ipv4(),
                'user_agent' => $faker->userAgent(),
            ]);

            // Add response details
            for ($qId = 8; $qId <= 11; $qId++) {
                ResponseDetail::create([
                    'response_id' => $response->id,
                    'question_id' => $qId,
                    'score' => $faker->numberBetween(1, 5),
                ]);
            }

            // Add suggestion (50% chance)
            if ($faker->boolean(50)) {
                Suggestion::create([
                    'response_id' => $response->id,
                    'comment' => $faker->randomElement($suggestions),
                ]);
            }
        }
    }
}
