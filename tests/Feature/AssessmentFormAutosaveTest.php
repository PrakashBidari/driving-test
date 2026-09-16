<?php

namespace Tests\Feature;

use App\Livewire\Forms\AssessmentForm;
use App\Models\AssessmentForm as AssessmentFormModel;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AssessmentFormAutosaveTest extends TestCase
{
    use RefreshDatabase;

    public function test_field_changes_persist_immediately_without_a_submit_action(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $student = Student::create(['full_name' => 'Jamie Driver', 'created_by' => $teacher->id]);
        $assessment = AssessmentFormModel::create([
            'student_id' => $student->id,
            'created_by' => $teacher->id,
            'status' => 'draft',
        ])->fresh();

        Livewire::actingAs($teacher)
            ->test(AssessmentForm::class, ['student' => $student, 'assessmentForm' => $assessment])
            ->set('data.eyesight', true)
            ->set('result', 'pass');

        $this->assertTrue((bool) $assessment->fresh()->data['eyesight']);
        $this->assertSame('pass', $assessment->fresh()->result);
    }

    public function test_a_teacher_cannot_open_another_students_assessment_via_mismatched_ids(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $studentA = Student::create(['full_name' => 'Student A', 'created_by' => $teacher->id]);
        $studentB = Student::create(['full_name' => 'Student B', 'created_by' => $teacher->id]);
        $assessment = AssessmentFormModel::create(['student_id' => $studentA->id, 'created_by' => $teacher->id]);

        Livewire::actingAs($teacher)
            ->test(AssessmentForm::class, ['student' => $studentB, 'assessmentForm' => $assessment])
            ->assertStatus(404);
    }
}
