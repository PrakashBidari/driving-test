<div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-lg font-semibold text-slate-800">Reflection Notes — {{ $student->full_name }}</h1>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('students.show', $student) }}" class="rounded-md bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">← {{ $student->full_name }}</a>
            @can('download-pdf')
            <a href="{{ route('pdf.reflection', [$student, $record]) }}" class="rounded-md bg-brand-50 px-3 py-2 text-sm font-medium text-brand-700 hover:bg-brand-100">⬇ PDF</a>
            @endcan
        </div>
    </div>

    <style>
        .rf-form{background:#fff;border:1px solid #b9c4d0;box-shadow:0 5px 20px rgba(20,45,70,.12);border-radius:8px;overflow:hidden}
        .rf-header{background:#123b70;color:#fff;padding:18px 22px}
        .rf-header h1{margin:0;font-size:19px}
        .rf-header p{margin:4px 0 0;font-size:12px;opacity:.9}
        .rf-identity{display:grid;grid-template-columns:1fr 1fr;gap:18px;padding:16px 22px;border-bottom:1px solid #c6d0da}
        .rf-field{display:flex;align-items:center;gap:8px}
        .rf-field label{font-weight:bold;font-size:13px;white-space:nowrap}
        .rf-field input,.rf-field .rf-static{flex:1;border:0;border-bottom:1px solid #788797;padding:6px 4px;font-size:14px;outline:none;min-width:0}
        .rf-section-title{background:#e8f0f7;color:#123b70;padding:10px 22px;font-weight:800;font-size:13px;border-top:1px solid #aebac6;border-bottom:1px solid #aebac6}
        .rf-content{padding:16px 22px}
        .rf-content textarea{width:100%;min-height:120px;border:1px solid #9eabb9;border-radius:4px;padding:11px;font-size:14px;line-height:1.55;resize:vertical;outline:none}
        .rf-prompt{font-size:12px;color:#516174;margin-bottom:8px}
        .rf-scale{display:grid;grid-template-columns:repeat(11,1fr);border:1px solid #aebac6;border-radius:4px;overflow:hidden}
        .rf-scale label{padding:8px 2px;text-align:center;border-right:1px solid #cbd4dd;font-size:12px;cursor:pointer}
        .rf-scale label:last-child{border-right:0}
        .rf-scale input{display:block;margin:0 auto 4px;accent-color:#123b70}
        .rf-scale-notes{display:flex;justify-content:space-between;font-size:11px;color:#4d5d70;margin-top:6px}
        @media(max-width:600px){.rf-identity{grid-template-columns:1fr}.rf-scale label{font-size:9px;padding:6px 0}}
    </style>

    <div x-data="{ tab: 1 }" class="space-y-4">
        <div class="rf-form">
            <header class="rf-header flex items-center justify-between gap-3">
                <div>
                    <h1>DRIVING LESSON REFLECTION NOTES</h1>
                    <p>Student self-reflection &amp; driving development record</p>
                </div>
                <span class="text-xs opacity-80" x-show="$wire.justSaved" x-transition x-cloak>✓ Saved</span>
            </header>

            <div class="rf-identity">
                <div class="rf-field"><label>Name:</label><span class="rf-static">{{ $student->full_name }}</span></div>
                <div class="rf-field"><label>Date:</label><input wire:model.live="lesson_date" type="date"></div>
            </div>

            <div class="flex gap-1 overflow-x-auto bg-white px-3 py-2">
                <button type="button" @click="tab = 1" :class="tab === 1 ? 'bg-brand-700 text-white' : 'text-slate-600 hover:bg-slate-100'" class="shrink-0 rounded-md px-3 py-2 text-xs font-semibold sm:text-sm">1. Went well</button>
                <button type="button" @click="tab = 2" :class="tab === 2 ? 'bg-brand-700 text-white' : 'text-slate-600 hover:bg-slate-100'" class="shrink-0 rounded-md px-3 py-2 text-xs font-semibold sm:text-sm">2. Do differently</button>
                <button type="button" @click="tab = 3" :class="tab === 3 ? 'bg-brand-700 text-white' : 'text-slate-600 hover:bg-slate-100'" class="shrink-0 rounded-md px-3 py-2 text-xs font-semibold sm:text-sm">3. Scale</button>
                <button type="button" @click="tab = 4" :class="tab === 4 ? 'bg-brand-700 text-white' : 'text-slate-600 hover:bg-slate-100'" class="shrink-0 rounded-md px-3 py-2 text-xs font-semibold sm:text-sm">4. Goals &amp; sign-off</button>
            </div>

            <div x-show="tab === 1" x-cloak>
                <div class="rf-section-title">1. WHAT WENT WELL TODAY?</div>
                <div class="rf-content">
                    <p class="rf-prompt">Consider what we have covered in today's lesson. Tell me what you thought you did well.</p>
                    <textarea wire:model.live.debounce.500ms="data.went_well" placeholder="Example: Manoeuvres – Bay Park, Parallel Park, Reverse Bay Park..."></textarea>
                </div>
            </div>

            <div x-show="tab === 2" x-cloak>
                <div class="rf-section-title">2. WHAT COULD WE DO DIFFERENTLY?</div>
                <div class="rf-content">
                    <p class="rf-prompt">If you could do anything differently, what would you like to have done better?</p>
                    <textarea wire:model.live.debounce.500ms="data.differently" placeholder="Example: Approaching junctions, gear changing, MSM – mirrors & signal..."></textarea>
                </div>
            </div>

            <div x-show="tab === 3" x-cloak>
                <div class="rf-section-title">3. SCALE CURRENT</div>
                <div class="rf-content">
                    <p class="font-semibold text-brand-700 mb-3">Rate your current driving standard from 0 to 10.</p>
                    <div class="rf-scale">
                        @for ($i = 0; $i <= 10; $i++)
                            <label><input type="radio" wire:model.live="scale" value="{{ $i }}">{{ $i }}</label>
                        @endfor
                    </div>
                    <div class="rf-scale-notes"><span><b>0</b> – Very Bad</span><span><b>10</b> – Very Good</span></div>
                </div>
            </div>

            <div x-show="tab === 4" x-cloak>
                <div class="rf-section-title">4. GOALS FOR NEXT LESSON</div>
                <div class="rf-content">
                    <p class="rf-prompt">What would you like to work on next time? Practice current development items, increase difficulty, or move on to a new subject?</p>
                    <textarea wire:model.live.debounce.500ms="data.goals" placeholder="Example: Big Roundabout (Spiral)"></textarea>

                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rf-field"><label>Instructor:</label><input wire:model.live.debounce.500ms="data.instructor"></div>
                        <div class="rf-field"><label>Next Lesson Date:</label><input wire:model.live="data.next_date" type="date"></div>
                    </div>

                    @can('fill-reflection-form')
                    <div class="mt-4 flex justify-end">
                        <button type="button" wire:click="markComplete" class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">
                            ✓ Mark reflection as completed
                        </button>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
