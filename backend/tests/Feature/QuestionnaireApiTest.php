<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Questionnaire;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QuestionnaireApiTest extends TestCase
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
    }

    public function test_get_all_questionnaires()
    {
        Questionnaire::create([
            'title' => 'Test Survey',
            'status' => 'active',
            'fiscal_year' => '2024',
            'created_by' => 1,
        ]);

        $response = $this->getJson('/api/surveys');
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_get_single_questionnaire()
    {
        $questionnaire = Questionnaire::create([
            'title' => 'Test Survey',
            'status' => 'active',
            'fiscal_year' => '2024',
            'created_by' => 1,
        ]);

        $response = $this->getJson("/api/surveys/{$questionnaire->id}");
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_create_questionnaire()
    {
        $response = $this->postJson('/api/surveys', [
            'title' => 'New Survey',
            'description' => 'Test',
            'fiscal_year' => '2024',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('questionnaires', ['title' => 'New Survey']);
    }

    public function test_validate_questionnaire_creation()
    {
        $response = $this->postJson('/api/surveys', [
            'title' => '', // Required field
            'fiscal_year' => '2024',
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('success', false);
    }
}
