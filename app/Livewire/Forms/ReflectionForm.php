<?php

namespace App\Livewire\Forms;

use App\Models\ReflectionForm as ReflectionFormModel;
use App\Models\Student;
use Livewire\Component;

class ReflectionForm extends Component
{
    public Student $student;

    public ReflectionFormModel $record;

    public string $lesson_date = '';

    public ?int $scale = null;

    public array $data = [];

    public bool $justSaved = false;

    public function mount(Student $student, ReflectionFormModel $reflectionForm): void
    {
        abort_unless($reflectionForm->student_id === $student->id, 404);

        $this->student = $student;
        $this->record = $reflectionForm;
        $this->lesson_date = $this->record->lesson_date?->format('Y-m-d') ?? '';
        $this->scale = $this->record->scale;
        $this->data = $this->record->data ?? [];
    }

    public function updated(string $name): void
    {
        $this->persist();
    }

    protected function persist(): void
    {
        $this->record->update([
            'lesson_date' => $this->lesson_date ?: null,
            'scale' => $this->scale,
            'updated_by' => auth()->id(),
            'data' => $this->data,
        ]);

        $this->justSaved = true;
    }

    public function markComplete(): void
    {
        $this->persist();
        $this->record->update(['status' => 'completed']);
        session()->flash('success', 'Reflection note marked as completed.');
    }

    public function render()
    {
        return view('livewire.forms.reflection-form')
            ->layout('layouts.app', ['title' => 'Reflection Notes — '.$this->student->full_name]);
    }
}
