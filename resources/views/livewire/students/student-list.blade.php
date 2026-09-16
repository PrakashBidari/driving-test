<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-lg font-semibold text-slate-800">Students</h1>
        <div class="flex flex-wrap items-center gap-2">
            @if (auth()->user()->isAdmin())
            <a href="{{ route('pdf.all') }}" class="rounded-md bg-brand-50 px-3 py-2 text-sm font-medium text-brand-700 hover:bg-brand-100">
                ⬇ Download all students (ZIP)
            </a>
            @endif
            @can('manage-students')
            <button wire:click="createStudent" class="rounded-md bg-brand-700 px-3 py-2 text-sm font-semibold text-white hover:bg-brand-600">
                + Add student
            </button>
            @endcan
        </div>
    </div>

    <div class="space-y-4">
        <div class="relative max-w-sm">
            <input wire:model.live.debounce.400ms="search" type="search" placeholder="Search by name, licence or phone..."
                   class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400">
        </div>

        {{-- Desktop table --}}
        <div class="hidden overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm lg:block">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Licence No.</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Vehicle</th>
                        <th class="px-4 py-3">Assessments</th>
                        <th class="px-4 py-3">Reflections</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-800">
                                <a href="{{ route('students.show', $student) }}" class="hover:underline">{{ $student->full_name }}</a>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $student->licence_no ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $student->phone ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $student->vehicle_reg ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $student->assessment_forms_count }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $student->reflection_forms_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('students.show', $student) }}" class="rounded-md bg-brand-50 px-2.5 py-1.5 text-xs font-medium text-brand-700 hover:bg-brand-100">View</a>
                                    @can('manage-students')
                                    <button wire:click="editStudent({{ $student->id }})" class="rounded-md bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200">Edit</button>
                                    <button wire:click="deleteStudent({{ $student->id }})" wire:confirm="Remove this student and all their records?" class="rounded-md bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100">Delete</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">No students found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile / tablet cards --}}
        <div class="space-y-3 lg:hidden">
            @forelse ($students as $student)
                <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <a href="{{ route('students.show', $student) }}" class="truncate font-semibold text-slate-800 hover:underline">{{ $student->full_name }}</a>
                            <p class="text-xs text-slate-500">{{ $student->licence_no ?: 'No licence no.' }}</p>
                        </div>
                        <div class="flex shrink-0 gap-2 text-xs text-slate-500">
                            <span>{{ $student->assessment_forms_count }} tests</span>
                        </div>
                    </div>
                    <dl class="mt-3 grid grid-cols-2 gap-2 text-xs text-slate-600">
                        <div><dt class="text-slate-400">Phone</dt><dd>{{ $student->phone ?: '—' }}</dd></div>
                        <div><dt class="text-slate-400">Vehicle</dt><dd>{{ $student->vehicle_reg ?: '—' }}</dd></div>
                    </dl>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a href="{{ route('students.show', $student) }}" class="rounded-md bg-brand-50 px-3 py-1.5 text-xs font-medium text-brand-700">View profile</a>
                        @can('manage-students')
                        <button wire:click="editStudent({{ $student->id }})" class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700">Edit</button>
                        <button wire:click="deleteStudent({{ $student->id }})" wire:confirm="Remove this student and all their records?" class="rounded-md bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700">Delete</button>
                        @endcan
                    </div>
                </div>
            @empty
                <p class="rounded-lg border border-dashed border-slate-300 bg-white px-4 py-8 text-center text-sm text-slate-500">No students found.</p>
            @endforelse
        </div>

        <div>{{ $students->links() }}</div>
    </div>

    {{-- Create / edit modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" wire:key="student-modal">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-lg bg-white shadow-xl">
                <form wire:submit="save">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <h3 class="font-semibold text-slate-800">{{ $editingId ? 'Edit student' : 'Add student' }}</h3>
                        <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>

                    <div class="space-y-4 px-5 py-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Full name *</label>
                            <input wire:model="full_name" type="text" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                            @error('full_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Licence No.</label>
                                <input wire:model="licence_no" type="text" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Date of birth</label>
                                <input wire:model="dob" type="date" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
                                <input wire:model="phone" type="text" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                                <input wire:model="email" type="email" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Vehicle Reg. No.</label>
                            <input wire:model="vehicle_reg" type="text" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Notes</label>
                            <textarea wire:model="notes" rows="3" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <button type="button" wire:click="closeModal" class="rounded-md bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="rounded-md bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">
                            {{ $editingId ? 'Save changes' : 'Add student' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
