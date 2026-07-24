<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Questionnaire;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ResponseApiTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'fullname' => 'Admin User',
            'role' => 'admin',
        ]);

        $questionnaire = Questionnaire::create([
            'title' => 'Test Survey',
            'status' => 'active',
            'fiscal_year' => '2024',
            'created_by' => 1,
        ]);

        $questionnaire->questions()->createMany([
            ['question' => 'Q1', 'category' => 'content', 'order_no' => 1],
            ['question' => 'Q2', 'category' => 'design', 'order_no' => 2],
        ]);
    }

    public function test_submit_response()
    {
        $response = $this->postJson('/api/responses', [
            'questionnaire_id' => 1,
            'gender' => 'male',
            'age' => '25-35',
            'user_type' => 'student',
            'responses' => [
                ['question_id' => 1, 'score' => 5],
                ['question_id' => 2, 'score' => 4],
            ],
            'comment' => 'Great survey!',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseHas('responses', ['gender' => 'male']);
    }

    public function test_submit_response_validation()
    {
        $response = $this->postJson('/api/responses', [
            'questionnaire_id' => 999, // Invalid ID
            'responses' => [],
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('success', false);
    }

    public function test_response_score_validation()
    {
        $response = $this->postJson('/api/responses', [
            'questionnaire_id' => 1,
            'responses' => [
                ['question_id' => 1, 'score' => 10], // Invalid score
            ],
        ]);

        $response->assertStatus(422);
    }
}
