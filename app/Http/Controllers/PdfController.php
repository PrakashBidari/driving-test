<?php

namespace App\Http\Controllers;

use App\Models\AssessmentForm;
use App\Models\ReflectionForm;
use App\Models\Student;
use App\Support\AssessmentFields;
use App\Support\ProgressSkills;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class PdfController extends Controller
{
    public function assessment(Student $student, AssessmentForm $assessmentForm)
    {
        abort_unless($assessmentForm->student_id === $student->id, 404);

        $bytes = $this->renderAssessmentPdf($assessmentForm, $student);

        return $this->streamPdf($bytes, $this->fileName($student, 'assessment', $assessmentForm->test_date?->format('Y-m-d')));
    }

    public function reflection(Student $student, ReflectionForm $reflectionForm)
    {
        abort_unless($reflectionForm->student_id === $student->id, 404);

        $bytes = $this->renderReflectionPdf($reflectionForm, $student);

        return $this->streamPdf($bytes, $this->fileName($student, 'reflection', $reflectionForm->lesson_date?->format('Y-m-d')));
    }

    public function progress(Student $student)
    {
        $progress = $student->progressRecord;

        abort_unless($progress, 404);

        $bytes = $this->renderProgressPdf($progress, $student);

        return $this->streamPdf($bytes, $this->fileName($student, 'progress-record'));
    }

    public function student(Student $student): BinaryFileResponse
    {
        $zipPath = storage_path('app/tmp/'.Str::uuid().'.zip');
        $this->ensureTmpDir();

        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE);

        $this->addStudentFilesToZip($zip, $student, '');

        $zip->close();

        return response()->download($zipPath, Str::slug($student->full_name).'-all-forms.zip')->deleteFileAfterSend(true);
    }

    public function allStudents(): BinaryFileResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $zipPath = storage_path('app/tmp/'.Str::uuid().'.zip');
        $this->ensureTmpDir();

        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE);

        Student::orderBy('full_name')->each(function (Student $student) use ($zip) {
            $folder = Str::slug($student->full_name.'-'.$student->id).'/';
            $this->addStudentFilesToZip($zip, $student, $folder);
        });

        $zip->close();

        return response()->download($zipPath, 'all-students-forms.zip')->deleteFileAfterSend(true);
    }

    protected function addStudentFilesToZip(ZipArchive $zip, Student $student, string $folder): void
    {
        $progress = $student->progressRecord;
        if ($progress) {
            $zip->addFromString($folder.'progress-record.pdf', $this->renderProgressPdf($progress, $student));
        }

        foreach ($student->assessmentForms as $assessment) {
            $name = 'assessment-'.($assessment->test_date?->format('Y-m-d') ?? $assessment->id).'.pdf';
            $zip->addFromString($folder.$name, $this->renderAssessmentPdf($assessment, $student));
        }

        foreach ($student->reflectionForms as $reflection) {
            $name = 'reflection-'.($reflection->lesson_date?->format('Y-m-d') ?? $reflection->id).'.pdf';
            $zip->addFromString($folder.$name, $this->renderReflectionPdf($reflection, $student));
        }
    }

    protected function renderAssessmentPdf(AssessmentForm $assessment, Student $student): string
    {
        $signatureBase64 = null;

        if ($assessment->signature_path && Storage::disk('public')->exists($assessment->signature_path)) {
            $binary = Storage::disk('public')->get($assessment->signature_path);
            $signatureBase64 = 'data:image/png;base64,'.base64_encode($binary);
        }

        return Pdf::loadView('pdf.assessment', [
            'student' => $student,
            'assessment' => $assessment,
            'tabs' => AssessmentFields::tabs(),
            'footerItems' => AssessmentFields::footerItems(),
            'signatureBase64' => $signatureBase64,
        ])->output();
    }

    protected function renderReflectionPdf(ReflectionForm $reflection, Student $student): string
    {
        return Pdf::loadView('pdf.reflection', [
            'student' => $student,
            'reflection' => $reflection,
        ])->output();
    }

    protected function renderProgressPdf($progress, Student $student): string
    {
        return Pdf::loadView('pdf.progress', [
            'student' => $student,
            'progress' => $progress,
            'groupsList' => ProgressSkills::groups(),
            'lessons' => ProgressSkills::LESSONS,
        ])->output();
    }

    protected function streamPdf(string $bytes, string $fileName)
    {
        return response($bytes, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$fileName.'"',
        ]);
    }

    protected function fileName(Student $student, string $type, ?string $date = null): string
    {
        return Str::slug($student->full_name).'-'.$type.($date ? '-'.$date : '').'.pdf';
    }

    protected function ensureTmpDir(): void
    {
        if (! is_dir(storage_path('app/tmp'))) {
            mkdir(storage_path('app/tmp'), 0755, true);
        }
    }
}
