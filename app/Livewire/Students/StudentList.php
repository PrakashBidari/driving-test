<?php

namespace App\Livewire\Students;

use App\Models\Student;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    #[Validate('required|string|max:255')]
    public string $full_name = '';

    #[Validate('nullable|string|max:100')]
    public string $licence_no = '';

    #[Validate('nullable|date')]
    public string $dob = '';

    #[Validate('nullable|string|max:30')]
    public string $phone = '';

    #[Validate('nullable|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:50')]
    public string $vehicle_reg = '';

    #[Validate('nullable|string|max:2000')]
    public string $notes = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function createStudent(): void
    {
        abort_unless(auth()->user()->can('manage-students'), 403);

        $this->resetForm();
        $this->showModal = true;
    }

    public function editStudent(int $studentId): void
    {
        abort_unless(auth()->user()->can('manage-students'), 403);

        $student = Student::findOrFail($studentId);
        $this->editingId = $student->id;
        $this->full_name = $student->full_name;
        $this->licence_no = (string) $student->licence_no;
        $this->dob = $student->dob?->format('Y-m-d') ?? '';
        $this->phone = (string) $student->phone;
        $this->email = (string) $student->email;
        $this->vehicle_reg = (string) $student->vehicle_reg;
        $this->notes = (string) $student->notes;
        $this->showModal = true;
    }

    public function save(): void
    {
        abort_unless(auth()->user()->can('manage-students'), 403);

        $data = $this->validate();
        $data['dob'] = $data['dob'] ?: null;

        if ($this->editingId) {
            Student::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Student updated successfully.');
        } else {
            $data['created_by'] = auth()->id();
            Student::create($data);
            session()->flash('success', 'Student added successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteStudent(int $studentId): void
    {
        abort_unless(auth()->user()->can('manage-students'), 403);

        Student::findOrFail($studentId)->delete();
        session()->flash('success', 'Student removed.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->full_name = '';
        $this->licence_no = '';
        $this->dob = '';
        $this->phone = '';
        $this->email = '';
        $this->vehicle_reg = '';
        $this->notes = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $students = Student::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('full_name', 'like', "%{$this->search}%")
                        ->orWhere('licence_no', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->withCount(['assessmentForms', 'reflectionForms'])
            ->orderBy('full_name')
            ->paginate(10);

        return view('livewire.students.student-list', [
            'students' => $students,
        ])->layout('layouts.app', ['title' => 'Students']);
    }
}
