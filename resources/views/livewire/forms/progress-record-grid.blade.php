<div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-lg font-semibold text-slate-800">Skill Progression — {{ $student->full_name }}</h1>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('students.show', $student) }}" class="rounded-md bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">← {{ $student->full_name }}</a>
            @can('download-pdf')
            <a href="{{ route('pdf.progress', $student) }}" class="rounded-md bg-brand-50 px-3 py-2 text-sm font-medium text-brand-700 hover:bg-brand-100">⬇ PDF</a>
            @endcan
        </div>
    </div>

    <style>
        .pr-card{background:#fff;border:1px solid #aebdca;border-radius:9px;overflow:hidden;box-shadow:0 4px 15px #123b7014}
        .pr-header{background:#123b70;color:#fff;padding:16px 20px}
        .pr-header h1{margin:0;font-size:20px}
        .pr-header p{margin:4px 0 0;font-size:12px;opacity:.85}
        .pr-info{display:grid;grid-template-columns:1fr 1fr;gap:18px;padding:16px 20px}
        .pr-field{display:flex;align-items:center;margin:6px 0}
        .pr-field label{width:110px;font-size:13px;font-weight:bold;flex-shrink:0}
        .pr-field input{flex:1;border:0;border-bottom:1px solid #8493a3;padding:6px;outline:0;min-width:0}
        .pr-section{background:#dcebf5;color:#123b70;font-weight:bold;padding:9px 14px;border-top:1px solid #9eb3c4;border-bottom:1px solid #9eb3c4;font-size:13px}
        .pr-scroll{overflow:auto}
        .pr-table{border-collapse:collapse;width:max-content;min-width:100%}
        .pr-table th,.pr-table td{border:1px solid #9eacb9;font-size:11px;text-align:center;padding:4px}
        .pr-table thead th{background:#dcebf5;color:#123b70;font-weight:bold;height:44px}
        .pr-skill{background:#edf5fa!important;text-align:left!important;font-weight:bold;position:sticky;left:0;z-index:2;min-width:180px;max-width:200px}
        .pr-lesson{min-width:70px}
        .pr-lesson input[type=date]{width:64px;font-size:9px;border:0;background:transparent}
        .pr-rating{width:16px;height:16px;accent-color:#1d63a8}
        .pr-legend{display:grid;grid-template-columns:repeat(5,1fr)}
        .pr-legend div{padding:10px;text-align:center;border-right:1px solid #c7d2dc;font-size:11px}
        .pr-legend b{display:block;font-size:17px;color:#123b70}
        .pr-legend .l1{background:#f0f2f4}.pr-legend .l2{background:#edf4fb}.pr-legend .l3{background:#fff4d8}.pr-legend .l4{background:#e9f6ed}.pr-legend .l5{background:#d9f0df}
        .pr-notes{padding:14px 20px;display:grid;grid-template-columns:1fr 1fr;gap:14px}
        .pr-notes textarea{width:100%;min-height:90px;border:1px solid #aebdca;border-radius:5px;padding:9px;resize:vertical}
        @media(max-width:700px){.pr-info,.pr-notes{grid-template-columns:1fr}.pr-legend{grid-template-columns:1fr 1fr}}
    </style>

    <div x-data="{ tab: 0 }" class="space-y-4">
        <div class="pr-card">
            <div class="pr-header">
                <h1>MBT DRIVERS RECORD</h1>
                <p>Driving Student Skill Development &amp; Progress Record — shared across every instructor</p>
            </div>
            <div class="pr-info">
                <div>
                    <div class="pr-field"><label>Student's Name:</label><span>{{ $student->full_name }}</span></div>
                    <div class="pr-field"><label>Licence No.:</label><span>{{ $student->licence_no ?: '—' }}</span></div>
                </div>
                <div>
                    <div class="pr-field"><label>Instructor:</label><input wire:model.live.debounce.500ms="instructor" placeholder="Current instructor"></div>
                    <div class="pr-field"><label>Vehicle Reg. No.:</label><input wire:model.live.debounce.500ms="vehicle"></div>
                </div>
            </div>

            <div class="flex items-center justify-between pr-section">
                <span>SKILL PROGRESSION RECORD — select one level per skill, per lesson</span>
                <span class="text-xs font-normal normal-case opacity-80" x-show="$wire.justSaved" x-transition x-cloak>✓ Saved</span>
            </div>

            {{-- Group tabs --}}
            <div class="flex gap-1 overflow-x-auto border-b border-slate-200 bg-white px-2 py-2">
                @foreach ($groups as $i => [$groupName, $skills])
                    <button type="button" @click="tab = {{ $i }}"
                            :class="tab === {{ $i }} ? 'bg-brand-700 text-white' : 'text-slate-600 hover:bg-slate-100'"
                            class="shrink-0 rounded-md px-3 py-2 text-xs font-semibold transition sm:text-sm">
                        {{ $groupName }}
                    </button>
                @endforeach
            </div>

            @foreach ($groups as $i => [$groupName, $skills])
                <div x-show="tab === {{ $i }}" x-cloak class="pr-scroll">
                    <table class="pr-table">
                        <thead>
                            <tr>
                                <th class="pr-skill">DRIVING SKILL</th>
                                @for ($lesson = 1; $lesson <= $lessons; $lesson++)
                                    <th class="pr-lesson">
                                        Lesson {{ $lesson }}<br>
                                        <input type="date" wire:model.live="data.dates.{{ $lesson }}" title="Date of lesson {{ $lesson }}">
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $rowOffset = collect($rows)->search(fn ($r) => $r['group'] === $groupName);
                            @endphp
                            @foreach ($skills as $skillIndex => $skillName)
                                @php $rowIndex = $rowOffset + $skillIndex; @endphp
                                <tr>
                                    <td class="pr-skill">{{ $skillName }}</td>
                                    @for ($lesson = 1; $lesson <= $lessons; $lesson++)
                                        <td>
                                            @foreach (['1', '2', '3', '4', '5'] as $level)
                                                <input class="pr-rating" type="radio"
                                                       wire:model.live="data.ratings.r{{ $rowIndex }}_{{ $lesson }}"
                                                       value="{{ $level }}"
                                                       title="{{ $level }}">
                                            @endforeach
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach

            <div class="pr-section">PROGRESS SCALE</div>
            <div class="pr-legend">
                <div class="l1"><b>1</b>Introduce</div>
                <div class="l2"><b>2</b>Under full instruction</div>
                <div class="l3"><b>3</b>Prompted</div>
                <div class="l4"><b>4</b>Seldom prompted</div>
                <div class="l5"><b>5</b>Independent</div>
            </div>

            <div class="pr-section">INSTRUCTOR NOTES / MUST-BE FOCUS AREAS</div>
            <div class="pr-notes">
                <textarea wire:model.live.debounce.500ms="data.focus" placeholder="Must-be focus areas, weaknesses, safety concerns and skills requiring further practice..."></textarea>
                <textarea wire:model.live.debounce.500ms="data.comments" placeholder="Instructor comments, progress observations and next lesson priorities..."></textarea>
            </div>
        </div>
    </div>
</div>
