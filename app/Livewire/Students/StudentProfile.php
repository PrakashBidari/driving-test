<?php

namespace App\Livewire\Students;

use App\Models\AssessmentForm;
use App\Models\ReflectionForm;
use App\Models\Student;
use Livewire\Component;

class StudentProfile extends Component
{
    public Student $student;

    public function mount(Student $student): void
    {
        $this->student = $student;
    }

    public function startAssessment()
    {
        abort_unless(auth()->user()->can('fill-assessment-form'), 403);

        $assessment = AssessmentForm::create([
            'student_id' => $this->student->id,
            'created_by' => auth()->id(),
            'test_date' => now()->format('Y-m-d'),
            'status' => 'draft',
        ]);

        return redirect()->route('assessment.edit', [$this->student, $assessment]);
    }

    public function startReflection()
    {
        abort_unless(auth()->user()->can('fill-reflection-form'), 403);

        $reflection = ReflectionForm::create([
            'student_id' => $this->student->id,
            'created_by' => auth()->id(),
            'lesson_date' => now()->format('Y-m-d'),
            'status' => 'draft',
        ]);

        return redirect()->route('reflection.edit', [$this->student, $reflection]);
    }

    public function render()
    {
        $assessments = $this->student->assessmentForms()->with('creator')->get();
        $reflections = $this->student->reflectionForms()->with('creator')->get();
        $progress = $this->student->progressRecord;

        return view('livewire.students.student-profile', [
            'assessments' => $assessments,
            'reflections' => $reflections,
            'progress' => $progress,
        ])->layout('layouts.app', ['title' => $this->student->full_name]);
    }
}
