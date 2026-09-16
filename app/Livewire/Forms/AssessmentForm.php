<?php

namespace App\Livewire\Forms;

use App\Models\AssessmentForm as AssessmentFormModel;
use App\Models\Student;
use App\Support\AssessmentFields;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class AssessmentForm extends Component
{
    public Student $student;

    public AssessmentFormModel $record;

    public string $test_date = '';

    public string $test_time = '';

    public string $result = 'pending';

    public array $data = [];

    public ?string $signaturePath = null;

    public bool $justSaved = false;

    public function mount(Student $student, AssessmentFormModel $assessmentForm): void
    {
        abort_unless($assessmentForm->student_id === $student->id, 404);

        $this->student = $student;
        $this->record = $assessmentForm;
        $this->test_date = $this->record->test_date?->format('Y-m-d') ?? '';
        $this->test_time = $this->record->data['time'] ?? '';
        $this->result = $this->record->result ?? 'pending';
        $this->signaturePath = $this->record->signature_path;
        $this->data = $this->record->data ?? [];
    }

    /** @return array<string, array{title: string, blocks: array<string, array<string, array{label: string, type: string}>>}> */
    public function tabs(): array
    {
        return AssessmentFields::tabs();
    }

    public function footerItems(): array
    {
        return AssessmentFields::footerItems();
    }

    public function updated(string $name): void
    {
        $this->persist();
    }

    protected function persist(): void
    {
        $this->record->update([
            'test_date' => $this->test_date ?: null,
            'result' => $this->result,
            'updated_by' => auth()->id(),
            'data' => array_merge($this->data, ['time' => $this->test_time]),
        ]);

        $this->flashSaved();
    }

    public function markComplete(): void
    {
        $this->persist();
        $this->record->update(['status' => 'completed']);
        session()->flash('success', 'Assessment marked as completed.');
    }

    protected function flashSaved(): void
    {
        $this->justSaved = true;
        $this->dispatch('assessment-saved');
    }

    #[On('signature-captured')]
    public function storeSignature(string $dataUrl): void
    {
        if (! preg_match('/^data:image\/png;base64,(.+)$/', $dataUrl, $matches)) {
            return;
        }

        $binary = base64_decode($matches[1]);
        $path = 'signatures/'.Str::uuid().'.png';
        Storage::disk('public')->put($path, $binary);

        if ($this->signaturePath) {
            Storage::disk('public')->delete($this->signaturePath);
        }

        $this->signaturePath = $path;
        $this->record->update(['signature_path' => $path]);
        $this->flashSaved();
    }

    #[On('signature-cleared')]
    public function clearSignature(): void
    {
        if ($this->signaturePath) {
            Storage::disk('public')->delete($this->signaturePath);
        }

        $this->signaturePath = null;
        $this->record->update(['signature_path' => null]);
    }

    public function render()
    {
        return view('livewire.forms.assessment-form', [
            'tabs' => $this->tabs(),
            'footerItems' => $this->footerItems(),
        ])->layout('layouts.app', ['title' => 'Assessment — '.$this->student->full_name]);
    }
}
