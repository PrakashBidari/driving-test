<div x-data="{ tab: 'assessment' }" class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-lg font-semibold text-slate-800">{{ $student->full_name }}</h1>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('students.index') }}" class="rounded-md bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">← All students</a>
            @can('download-pdf')
            <a href="{{ route('pdf.student', $student) }}" class="rounded-md bg-brand-700 px-3 py-2 text-sm font-semibold text-white hover:bg-brand-600">
                ⬇ Download all (ZIP)
            </a>
            @endcan
        </div>
    </div>
        {{-- Student info card --}}
        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">{{ $student->full_name }}</h2>
                    <p class="text-sm text-slate-500">Licence No. {{ $student->licence_no ?: '—' }}</p>
                </div>
            </div>
            <dl class="mt-4 grid grid-cols-2 gap-3 text-sm sm:grid-cols-4">
                <div><dt class="text-xs text-slate-400">Phone</dt><dd class="text-slate-700">{{ $student->phone ?: '—' }}</dd></div>
                <div><dt class="text-xs text-slate-400">Email</dt><dd class="text-slate-700">{{ $student->email ?: '—' }}</dd></div>
                <div><dt class="text-xs text-slate-400">Vehicle Reg.</dt><dd class="text-slate-700">{{ $student->vehicle_reg ?: '—' }}</dd></div>
                <div><dt class="text-xs text-slate-400">Date of birth</dt><dd class="text-slate-700">{{ $student->dob?->format('d M Y') ?? '—' }}</dd></div>
            </dl>
            @if ($student->notes)
                <p class="mt-3 rounded-md bg-slate-50 p-3 text-sm text-slate-600">{{ $student->notes }}</p>
            @endif
        </div>

        {{-- Tab strip --}}
        <div class="flex gap-1 overflow-x-auto rounded-lg border border-slate-200 bg-white p-1 shadow-sm">
            <button @click="tab = 'assessment'" :class="tab === 'assessment' ? 'bg-brand-700 text-white' : 'text-slate-600 hover:bg-slate-100'" class="shrink-0 rounded-md px-3 py-2 text-sm font-medium transition">
                📋 Assessment tests <span class="ml-1 opacity-70">({{ $assessments->count() }})</span>
            </button>
            <button @click="tab = 'progress'" :class="tab === 'progress' ? 'bg-brand-700 text-white' : 'text-slate-600 hover:bg-slate-100'" class="shrink-0 rounded-md px-3 py-2 text-sm font-medium transition">
                📈 Skill progression
            </button>
            <button @click="tab = 'reflection'" :class="tab === 'reflection' ? 'bg-brand-700 text-white' : 'text-slate-600 hover:bg-slate-100'" class="shrink-0 rounded-md px-3 py-2 text-sm font-medium transition">
                📝 Reflection notes <span class="ml-1 opacity-70">({{ $reflections->count() }})</span>
            </button>
        </div>

        {{-- Assessment tab --}}
        <div x-show="tab === 'assessment'" x-cloak class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                <h3 class="font-semibold text-slate-800">Driving Lesson Assessment history</h3>
                @can('fill-assessment-form')
                <button wire:click="startAssessment" class="rounded-md bg-brand-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-600">+ New test</button>
                @endcan
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($assessments as $assessment)
                    <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-slate-800">
                                {{ $assessment->test_date?->format('d M Y') ?? 'Draft — no date yet' }}
                                <span @class([
                                    'ml-2 rounded-full px-2 py-0.5 text-xs font-semibold',
                                    'bg-green-100 text-green-700' => $assessment->result === 'pass',
                                    'bg-red-100 text-red-700' => $assessment->result === 'fail',
                                    'bg-slate-100 text-slate-600' => $assessment->result === 'pending',
                                ])>{{ ucfirst($assessment->result) }}</span>
                                @if ($assessment->status === 'draft')
                                    <span class="ml-1 rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700">Draft</span>
                                @endif
                            </p>
                            <p class="text-xs text-slate-500">Tested by {{ $assessment->creator?->name ?? '—' }}</p>
                        </div>
                        <div class="flex shrink-0 gap-2">
                            <a href="{{ route('assessment.edit', [$student, $assessment]) }}" class="rounded-md bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200">Open</a>
                            @can('download-pdf')
                            <a href="{{ route('pdf.assessment', [$student, $assessment]) }}" class="rounded-md bg-brand-50 px-2.5 py-1.5 text-xs font-medium text-brand-700 hover:bg-brand-100">PDF</a>
                            @endcan
                        </div>
                    </div>
                @empty
                    <p class="px-4 py-8 text-center text-sm text-slate-500">No assessment tests recorded yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Progress tab --}}
        <div x-show="tab === 'progress'" x-cloak class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="font-semibold text-slate-800">MBT Drivers Record — skill progression</h3>
                    <p class="text-sm text-slate-500">
                        One shared record updated across every lesson. Any instructor can continue it.
                        @if ($progress) Last updated {{ $progress->updated_at->diffForHumans() }} by {{ $progress->updater?->name ?? '—' }}. @endif
                    </p>
                </div>
                <div class="flex gap-2">
                    @can('fill-progress-record')
                    <a href="{{ route('progress.show', $student) }}" class="rounded-md bg-brand-700 px-3 py-1.5 text-sm font-semibold text-white hover:bg-brand-600">
                        {{ $progress ? 'Open record' : 'Start record' }}
                    </a>
                    @endcan
                    @can('download-pdf')
                    @if ($progress)
                    <a href="{{ route('pdf.progress', $student) }}" class="rounded-md bg-brand-50 px-3 py-1.5 text-sm font-medium text-brand-700 hover:bg-brand-100">PDF</a>
                    @endif
                    @endcan
                </div>
            </div>
        </div>

        {{-- Reflection tab --}}
        <div x-show="tab === 'reflection'" x-cloak class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                <h3 class="font-semibold text-slate-800">Lesson Reflection Notes history</h3>
                @can('fill-reflection-form')
                <button wire:click="startReflection" class="rounded-md bg-brand-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-600">+ New reflection</button>
                @endcan
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($reflections as $reflection)
                    <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-slate-800">
                                {{ $reflection->lesson_date?->format('d M Y') ?? 'Draft — no date yet' }}
                                <span class="ml-2 text-xs text-slate-500">Scale: {{ $reflection->scale ?? '—' }}/10</span>
                                @if ($reflection->status === 'draft')
                                    <span class="ml-1 rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700">Draft</span>
                                @endif
                            </p>
                            <p class="text-xs text-slate-500">By {{ $reflection->creator?->name ?? '—' }}</p>
                        </div>
                        <div class="flex shrink-0 gap-2">
                            <a href="{{ route('reflection.edit', [$student, $reflection]) }}" class="rounded-md bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200">Open</a>
                            @can('download-pdf')
                            <a href="{{ route('pdf.reflection', [$student, $reflection]) }}" class="rounded-md bg-brand-50 px-2.5 py-1.5 text-xs font-medium text-brand-700 hover:bg-brand-100">PDF</a>
                            @endcan
                        </div>
                    </div>
                @empty
                    <p class="px-4 py-8 text-center text-sm text-slate-500">No reflection notes recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
