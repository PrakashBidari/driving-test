<?php

use App\Http\Controllers\PdfController;
use App\Livewire\Dashboard;
use App\Livewire\Forms\AssessmentForm;
use App\Livewire\Forms\ProgressRecordGrid;
use App\Livewire\Forms\ReflectionForm;
use App\Livewire\Roles\RoleManager;
use App\Livewire\Students\StudentList;
use App\Livewire\Students\StudentProfile;
use App\Livewire\Users\UserManager;
use Illuminate\Support\Facades\Route;

Route::pattern('student', '[0-9]+');
Route::pattern('assessmentForm', '[0-9]+');
Route::pattern('reflectionForm', '[0-9]+');

Route::get('/', function () {
    return redirect()->to(auth()->check() ? '/dashboard' : '/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');

    Route::middleware('permission:view-students')->group(function () {
        Route::get('/students', StudentList::class)->name('students.index');
        Route::get('/students/{student}', StudentProfile::class)->name('students.show');
    });

    Route::middleware('permission:fill-assessment-form')->group(function () {
        Route::get('/students/{student}/assessment/{assessmentForm}', AssessmentForm::class)->name('assessment.edit');
    });

    Route::middleware('permission:fill-progress-record')->group(function () {
        Route::get('/students/{student}/progress', ProgressRecordGrid::class)->name('progress.show');
    });

    Route::middleware('permission:fill-reflection-form')->group(function () {
        Route::get('/students/{student}/reflection/{reflectionForm}', ReflectionForm::class)->name('reflection.edit');
    });

    Route::middleware('permission:download-pdf')->group(function () {
        Route::get('/students/{student}/assessment/{assessmentForm}/pdf', [PdfController::class, 'assessment'])->name('pdf.assessment');
        Route::get('/students/{student}/reflection/{reflectionForm}/pdf', [PdfController::class, 'reflection'])->name('pdf.reflection');
        Route::get('/students/{student}/progress/pdf', [PdfController::class, 'progress'])->name('pdf.progress');
        Route::get('/students/{student}/pdf', [PdfController::class, 'student'])->name('pdf.student');
        Route::get('/students/pdf-all', [PdfController::class, 'allStudents'])->name('pdf.all');
    });

    Route::middleware('permission:manage-students')->group(function () {
        // Create/edit/delete happen inline via the StudentList/StudentProfile Livewire components.
    });

    Route::middleware('permission:manage-users')->group(function () {
        Route::get('/users', UserManager::class)->name('users.index');
    });

    Route::middleware('permission:manage-roles')->group(function () {
        Route::get('/roles', RoleManager::class)->name('roles.index');
    });
});
