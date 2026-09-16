<?php

namespace App\Livewire;

use App\Models\AssessmentForm;
use App\Models\ReflectionForm;
use App\Models\Student;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $stats = [
            'students' => Student::count(),
            'assessments' => AssessmentForm::count(),
            'passed' => AssessmentForm::where('result', 'pass')->count(),
            'failed' => AssessmentForm::where('result', 'fail')->count(),
        ];

        if ($user->isAdmin()) {
            $stats['teachers'] = User::role('teacher')->count();
            $stats['admins'] = User::role('admin')->count();
        }

        $recentAssessments = AssessmentForm::with(['student', 'creator'])
            ->latest('updated_at')
            ->limit(8)
            ->get();

        $recentReflections = ReflectionForm::with(['student', 'creator'])
            ->latest('updated_at')
            ->limit(5)
            ->get();

        $recentStudents = Student::latest()->limit(5)->get();

        return view('livewire.dashboard', [
            'stats' => $stats,
            'recentAssessments' => $recentAssessments,
            'recentReflections' => $recentReflections,
            'recentStudents' => $recentStudents,
        ])->layout('layouts.app', ['title' => 'Dashboard']);
    }
}
