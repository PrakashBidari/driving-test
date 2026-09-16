<div class="space-y-6">
    <h1 class="text-lg font-semibold text-slate-800">Dashboard</h1>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Students</p>
                <p class="mt-1 text-2xl font-bold text-brand-700">{{ $stats['students'] }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Assessments</p>
                <p class="mt-1 text-2xl font-bold text-brand-700">{{ $stats['assessments'] }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Passed</p>
                <p class="mt-1 text-2xl font-bold text-green-600">{{ $stats['passed'] }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Failed</p>
                <p class="mt-1 text-2xl font-bold text-red-600">{{ $stats['failed'] }}</p>
            </div>

            @can('manage-users')
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Teachers</p>
                <p class="mt-1 text-2xl font-bold text-brand-700">{{ $stats['teachers'] }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Admins</p>
                <p class="mt-1 text-2xl font-bold text-brand-700">{{ $stats['admins'] }}</p>
            </div>
            @endcan
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <h2 class="font-semibold text-slate-800">Recent assessment tests</h2>
                    @can('view-students')
                    <a href="{{ route('students.index') }}" class="text-sm text-brand-700 hover:underline">View all students</a>
                    @endcan
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($recentAssessments as $assessment)
                        <a href="{{ route('students.show', $assessment->student_id) }}" class="flex items-center justify-between gap-3 px-4 py-3 hover:bg-slate-50">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-slate-800">{{ $assessment->student->full_name }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $assessment->test_date?->format('d M Y') ?? 'No date' }} ·
                                    by {{ $assessment->creator?->name ?? '—' }}
                                </p>
                            </div>
                            <span @class([
                                'shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold',
                                'bg-green-100 text-green-700' => $assessment->result === 'pass',
                                'bg-red-100 text-red-700' => $assessment->result === 'fail',
                                'bg-slate-100 text-slate-600' => $assessment->result === 'pending',
                            ])>
                                {{ ucfirst($assessment->result) }}
                            </span>
                        </a>
                    @empty
                        <p class="px-4 py-6 text-center text-sm text-slate-500">No assessments recorded yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-4 py-3">
                    <h2 class="font-semibold text-slate-800">Newest students</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($recentStudents as $student)
                        <a href="{{ route('students.show', $student) }}" class="flex items-center justify-between gap-2 px-4 py-3 hover:bg-slate-50">
                            <span class="truncate text-sm font-medium text-slate-800">{{ $student->full_name }}</span>
                            <span class="shrink-0 text-xs text-slate-400">{{ $student->licence_no }}</span>
                        </a>
                    @empty
                        <p class="px-4 py-6 text-center text-sm text-slate-500">No students yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-4 py-3">
                <h2 class="font-semibold text-slate-800">Recent reflection notes</h2>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($recentReflections as $reflection)
                    <a href="{{ route('students.show', $reflection->student_id) }}" class="flex flex-wrap items-center justify-between gap-2 px-4 py-3 hover:bg-slate-50">
                        <span class="text-sm font-medium text-slate-800">{{ $reflection->student->full_name }}</span>
                        <span class="text-xs text-slate-500">{{ $reflection->lesson_date?->format('d M Y') ?? 'No date' }} · Scale: {{ $reflection->scale ?? '—' }}/10</span>
                    </a>
                @empty
                    <p class="px-4 py-6 text-center text-sm text-slate-500">No reflection notes yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
