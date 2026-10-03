<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_students(): void
    {
        Student::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/students');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'date_of_birth',
                        'age',
                        'gender',
                        'formatted_gender',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'meta' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page',
                ],
            ]);
    }

    public function test_can_create_student(): void
    {
        $payload = [
            'name' => 'John Doe',
            'date_of_birth' => '2000-05-15',
            'gender' => 'male',
        ];

        $response = $this->postJson('/api/v1/students', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'John Doe')
            ->assertJsonPath('data.gender', 'male');

        $this->assertDatabaseHas('students', [
            'name' => 'John Doe',
            'gender' => 'male',
        ]);
    }

    public function test_can_show_student(): void
    {
        $student = Student::factory()->create([
            'name' => 'Jane Smith',
            'date_of_birth' => '1998-12-10',
            'gender' => 'female',
        ]);

        $response = $this->getJson("/api/v1/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $student->id)
            ->assertJsonPath('data.name', 'Jane Smith');
    }

    public function test_can_update_student(): void
    {
        $student = Student::factory()->create([
            'name' => 'Old Name',
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
        ]);

        $payload = [
            'name' => 'Updated Name',
        ];

        $response = $this->putJson("/api/v1/students/{$student->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Updated Name');

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_can_delete_student(): void
    {
        $student = Student::factory()->create();

        $response = $this->deleteJson("/api/v1/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('students', [
            'id' => $student->id,
        ]);
    }
}
