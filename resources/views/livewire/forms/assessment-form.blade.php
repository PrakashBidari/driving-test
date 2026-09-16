<div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-lg font-semibold text-slate-800">Assessment — {{ $student->full_name }}</h1>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('students.show', $student) }}" class="rounded-md bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">← {{ $student->full_name }}</a>
            @can('download-pdf')
            <a href="{{ route('pdf.assessment', [$student, $record]) }}" class="rounded-md bg-brand-50 px-3 py-2 text-sm font-medium text-brand-700 hover:bg-brand-100">⬇ PDF</a>
            @endcan
        </div>
    </div>

    <style>
        .af-sheet{background:#fff;border:1px solid #9eabb5;border-radius:10px;box-shadow:0 5px 20px #0001;overflow:hidden}
        .af-top{display:grid;grid-template-columns:1fr 1fr;padding:16px 18px;border-bottom:2px solid #273944;gap:16px}
        .af-topgrid{display:grid;grid-template-columns:1fr 1fr;gap:8px 20px}
        .af-field{display:flex;align-items:center;gap:8px}
        .af-field label{font-weight:bold;font-size:13px;min-width:65px}
        .af-field input,.af-field .af-static{flex:1;border:0;border-bottom:1px solid #657581;padding:6px;outline:none;background:transparent}
        .af-block{border:1px solid #81909a;border-radius:6px;overflow:hidden;margin-bottom:12px}
        .af-block h3{font-size:14px;margin:0;padding:8px 10px;background:#eef2f4;border-bottom:1px solid #aab5bc;color:#182f3c}
        .af-item{display:grid;grid-template-columns:1fr 52px 52px;align-items:center;min-height:39px;border-bottom:1px solid #d0d7dc;padding:4px 8px;font-size:13px}
        .af-item:last-child{border-bottom:0}
        .af-check{width:23px;height:23px;accent-color:#173d58}
        .af-sd{font-weight:bold;text-align:center;font-size:12px;display:flex;align-items:center;justify-content:center;gap:4px}
        .af-sd input{width:20px;height:20px;accent-color:#173d58}
        .af-manoeuvres{padding:8px;display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:12px}
        .af-manoeuvres label{border:1px solid #c0cbd2;border-radius:4px;padding:7px;display:flex;gap:7px;align-items:center}
        .af-footer{border:1px solid #8e9ca5;border-radius:6px;display:grid;grid-template-columns:repeat(2,1fr);gap:0;overflow:hidden}
        .af-footitem{padding:10px;border-right:1px solid #aeb9c0;border-bottom:1px solid #aeb9c0;font-size:12px}
        .af-result{display:grid;grid-template-columns:1fr 1fr;border:1px solid #8e9ca5;border-radius:6px;overflow:hidden}
        .af-result > div{padding:12px;border-right:1px solid #aeb9c0}
        .af-result > div:last-child{border:0}
        .af-result label{margin-right:14px;font-weight:bold;font-size:13px}
        .af-notes textarea{width:100%;min-height:90px;border:1px solid #9eabb5;border-radius:5px;padding:9px}
        @media(min-width:640px){.af-manoeuvres{grid-template-columns:1fr 1fr}}
        @media(min-width:900px){.af-2col{display:grid;grid-template-columns:1fr 1fr;gap:14px}}
        @media(max-width:900px){.af-top{grid-template-columns:1fr}}
        @media(max-width:560px){
            .af-topgrid{grid-template-columns:1fr}
            .af-footer{grid-template-columns:1fr}
            .af-result{grid-template-columns:1fr}
            .af-item{grid-template-columns:1fr 44px 44px}
            .af-field label{min-width:auto}
        }
    </style>

    <div x-data="{ tab: 'eyesight' }" class="space-y-4">
        {{-- Header --}}
        <div class="af-sheet">
            <div class="af-top">
                <div class="af-topgrid">
                    <div class="af-field"><label>Name</label><span class="af-static">{{ $student->full_name }}</span></div>
                    <div class="af-field"><label>Licence no.</label><span class="af-static">{{ $student->licence_no ?: '—' }}</span></div>
                    <div class="af-field"><label>Date</label><input wire:model.live="test_date" type="date"></div>
                    <div class="af-field"><label>Time</label><input wire:model.live="test_time" type="time"></div>
                </div>
                <div class="flex flex-col justify-center gap-1 text-sm">
                    <span class="font-semibold text-slate-600">
                        Status:
                        <span @class(['rounded-full px-2 py-0.5 text-xs font-semibold', 'bg-amber-100 text-amber-700' => $record->status === 'draft', 'bg-green-100 text-green-700' => $record->status === 'completed'])>
                            {{ ucfirst($record->status) }}
                        </span>
                    </span>
                    <span class="text-xs text-slate-400" x-show="$wire.justSaved" x-transition x-cloak>✓ All changes saved</span>
                </div>
            </div>
        </div>

        {{-- Tab strip --}}
        <div class="flex gap-1 overflow-x-auto rounded-lg border border-slate-200 bg-white p-1 shadow-sm">
            @foreach ($tabs as $key => $tabDef)
                <button type="button" @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}' ? 'bg-brand-700 text-white' : 'text-slate-600 hover:bg-slate-100'"
                        class="shrink-0 rounded-md px-3 py-2 text-sm font-medium transition">
                    {{ $tabDef['title'] }}
                </button>
            @endforeach
            <button type="button" @click="tab = 'result'"
                    :class="tab === 'result' ? 'bg-brand-700 text-white' : 'text-slate-600 hover:bg-slate-100'"
                    class="shrink-0 rounded-md px-3 py-2 text-sm font-medium transition">
                Result &amp; Sign-off
            </button>
        </div>

        {{-- Tab panels --}}
        @foreach ($tabs as $key => $tabDef)
            <div x-show="tab === '{{ $key }}'" x-cloak class="af-sheet p-4">
                <div class="af-2col">
                    @foreach ($tabDef['blocks'] as $blockTitle => $fields)
                        <div class="af-block">
                            <h3>{{ $blockTitle }}</h3>
                            @foreach ($fields as $fieldKey => $field)
                                @if ($field['type'] === 'check')
                                    <div class="af-item">
                                        <span>{{ $field['label'] }}</span><span></span>
                                        <span class="text-right"><input class="af-check" type="checkbox" wire:model.live="data.{{ $fieldKey }}"></span>
                                    </div>
                                @else
                                    <div class="af-item">
                                        <span>{{ $field['label'] }}</span>
                                        <span class="af-sd">S <input type="radio" wire:model.live="data.{{ $fieldKey }}" value="S"></span>
                                        <span class="af-sd">D <input type="radio" wire:model.live="data.{{ $fieldKey }}" value="D"></span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach

                    @if ($key === 'eyesight')
                        {{-- nothing extra --}}
                    @endif

                    @if ($key === 'awareness')
                        <div class="af-block">
                            <h3>Total faults</h3>
                            <div class="af-item" style="grid-template-columns:1fr auto">
                                <span>Fault count</span>
                                <input wire:model.live.debounce.500ms="data.faults" type="number" min="0" style="width:70px;padding:5px;border:1px solid #aab6bf;border-radius:4px">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- Result & sign-off tab --}}
        <div x-show="tab === 'result'" x-cloak class="space-y-4">
            <div class="af-result">
                <div>
                    <b class="mb-2 block text-sm">Result</b>
                    <label><input type="radio" wire:model.live="result" value="pass"> Pass</label>
                    <label><input type="radio" wire:model.live="result" value="fail"> Fail</label>
                </div>
                <div>
                    <b class="mb-2 block text-sm">Notes / Must-be Focus Area</b>
                    <input wire:model.live.debounce.500ms="data.focus" style="width:100%;padding:7px;border:1px solid #aab6bf;border-radius:4px">
                </div>
            </div>

            <div class="af-footer">
                @foreach ($footerItems as $fieldKey => $label)
                    <div class="af-footitem"><label><input type="checkbox" wire:model.live="data.{{ $fieldKey }}"> {{ $label }}</label></div>
                @endforeach
            </div>

            <div class="af-notes af-sheet p-4">
                <label class="mb-1 block text-sm font-semibold text-slate-700">Instructor remarks</label>
                <textarea wire:model.live.debounce.500ms="data.remarks" placeholder="Instructor remarks / development points / next lesson focus..."></textarea>
            </div>

            <div class="af-sheet p-4">
                <label class="mb-2 block text-sm font-semibold text-slate-700">Signature</label>
                <livewire:forms.signature-pad :existing-url="$signaturePath ? \Illuminate\Support\Facades\Storage::url($signaturePath) : null" :key="'sig-'.$record->id.'-'.($signaturePath ?? 'none')" />
            </div>

            @can('fill-assessment-form')
            <div class="flex justify-end">
                <button type="button" wire:click="markComplete" class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">
                    ✓ Mark test as completed
                </button>
            </div>
            @endcan
        </div>
    </div>
</div>
