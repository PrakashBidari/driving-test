<?php

namespace App\Livewire\Forms;

use App\Models\ProgressRecord;
use App\Models\Student;
use App\Support\ProgressSkills;
use Livewire\Component;

class ProgressRecordGrid extends Component
{
    public Student $student;

    public ProgressRecord $record;

    public string $instructor = '';

    public string $vehicle = '';

    public array $data = [];

    public bool $justSaved = false;

    public function mount(Student $student): void
    {
        $this->student = $student;

        $this->record = ProgressRecord::firstOrCreate(
            ['student_id' => $student->id],
            ['updated_by' => auth()->id(), 'data' => []]
        );

        $this->data = $this->record->data ?? [];
        $this->instructor = $this->data['header']['instructor'] ?? '';
        $this->vehicle = $this->data['header']['vehicle'] ?? $student->vehicle_reg ?? '';
    }

    public function updated(string $name): void
    {
        $this->persist();
    }

    protected function persist(): void
    {
        $this->data['header'] = [
            'instructor' => $this->instructor,
            'vehicle' => $this->vehicle,
        ];

        $this->record->update([
            'updated_by' => auth()->id(),
            'data' => $this->data,
        ]);

        $this->justSaved = true;
    }

    public function render()
    {
        return view('livewire.forms.progress-record-grid', [
            'groups' => ProgressSkills::groups(),
            'rows' => ProgressSkills::rows(),
            'lessons' => ProgressSkills::LESSONS,
        ])->layout('layouts.app', ['title' => 'Skill Progression — '.$this->student->full_name]);
    }
}
