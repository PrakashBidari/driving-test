<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; color: #1c2a36; }
    h1 { font-size: 16px; color: #123b70; margin: 0 0 2px; }
    .muted { color: #667685; font-size: 10px; }
    table.header-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    table.header-table td { padding: 4px 6px; font-size: 10px; border: 1px solid #c7d2dc; }
    table.header-table td.label { font-weight: bold; width: 90px; background: #eef2f4; }
    table.grid { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    table.grid th { background: #123b70; color: #fff; font-size: 10px; padding: 4px 6px; text-align: left; }
    table.grid td { border: 1px solid #c7d2dc; padding: 3px 6px; font-size: 9.5px; }
    table.grid td.value { text-align: center; width: 60px; font-weight: bold; }
    .section-title { background: #dcebf5; color: #123b70; font-weight: bold; padding: 4px 6px; font-size: 11px; margin-top: 8px; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 8px; font-weight: bold; font-size: 10px; }
    .pass { background: #d9f0df; color: #17733f; }
    .fail { background: #fbd9d9; color: #a11d1d; }
    .pending { background: #eef2f4; color: #55636e; }
    .signature-box { border: 1px solid #c7d2dc; padding: 6px; margin-top: 6px; }
    .signature-box img { height: 60px; }
    .footer-grid { width: 100%; border-collapse: collapse; margin-top: 6px; }
    .footer-grid td { border: 1px solid #c7d2dc; padding: 4px 6px; font-size: 10px; }
    .yes { color: #17733f; font-weight: bold; }
    .no { color: #99a3ac; }
</style>
</head>
<body>
    <h1>Driving Lesson Assessment Form</h1>
    <p class="muted">Generated {{ now()->format('d M Y H:i') }}</p>

    <table class="header-table">
        <tr>
            <td class="label">Name</td><td>{{ $student->full_name }}</td>
            <td class="label">Licence No.</td><td>{{ $student->licence_no ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Date</td><td>{{ $assessment->test_date?->format('d M Y') ?? '-' }}</td>
            <td class="label">Time</td><td>{{ $assessment->data['time'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tested by</td><td>{{ $assessment->creator?->name ?? '-' }}</td>
            <td class="label">Result</td>
            <td>
                <span class="badge {{ $assessment->result }}">{{ strtoupper($assessment->result) }}</span>
            </td>
        </tr>
    </table>

    @foreach ($tabs as $tabDef)
        <div class="section-title">{{ $tabDef['title'] }}</div>
        @foreach ($tabDef['blocks'] as $blockTitle => $fields)
            <table class="grid">
                <tr><th colspan="2">{{ $blockTitle }}</th></tr>
                @foreach ($fields as $fieldKey => $field)
                    <tr>
                        <td>{{ $field['label'] }}</td>
                        <td class="value">
                            @if ($field['type'] === 'check')
                                <span class="{{ ! empty($assessment->data[$fieldKey]) ? 'yes' : 'no' }}">{{ ! empty($assessment->data[$fieldKey]) ? 'YES' : 'no' }}</span>
                            @else
                                {{ $assessment->data[$fieldKey] ?? '-' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        @endforeach
    @endforeach

    <div class="section-title">Total faults</div>
    <table class="grid"><tr><td>Fault count</td><td class="value">{{ $assessment->data['faults'] ?? '0' }}</td></tr></table>

    <div class="section-title">Notes / Must-be Focus Area</div>
    <table class="grid"><tr><td>{{ $assessment->data['focus'] ?? '-' }}</td></tr></table>

    <div class="section-title">Checklist</div>
    <table class="footer-grid">
        <tr>
            @foreach ($footerItems as $fieldKey => $label)
                <td><span class="{{ ! empty($assessment->data[$fieldKey]) ? 'yes' : 'no' }}">{{ ! empty($assessment->data[$fieldKey]) ? '[x]' : '[ ]' }}</span> {{ $label }}</td>
            @endforeach
        </tr>
    </table>

    <div class="section-title">Instructor remarks</div>
    <table class="grid"><tr><td>{{ $assessment->data['remarks'] ?? '-' }}</td></tr></table>

    <div class="section-title">Signature</div>
    <div class="signature-box">
        @if ($signatureBase64)
            <img src="{{ $signatureBase64 }}" alt="Signature">
        @else
            <span class="muted">No signature on file.</span>
        @endif
    </div>
</body>
</html>
