<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Questionnaire;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QuestionnaireModelTest extends TestCase
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

    public function test_questionnaire_can_be_created()
    {
        $questionnaire = Questionnaire::create([
            'title' => 'Test Survey',
            'description' => 'Test Description',
            'status' => 'active',
            'fiscal_year' => '2024',
            'created_by' => 1,
        ]);

        $this->assertDatabaseHas('questionnaires', [
            'title' => 'Test Survey',
            'status' => 'active',
        ]);
    }

    public function test_questionnaire_has_questions()
    {
        $questionnaire = Questionnaire::create([
            'title' => 'Test Survey',
            'description' => 'Test Description',
            'status' => 'active',
            'fiscal_year' => '2024',
            'created_by' => 1,
        ]);

        $questionnaire->questions()->create([
            'question' => 'Test Question',
            'category' => 'content',
            'order_no' => 1,
        ]);

        $this->assertCount(1, $questionnaire->questions);
    }
}
