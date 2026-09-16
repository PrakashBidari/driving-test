<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Helvetica, Arial, sans-serif; font-size: 9px; color: #172638; }
    h1 { font-size: 15px; color: #123b70; margin: 0 0 2px; }
    .muted { color: #667685; font-size: 9px; }
    table.header-table { width: 100%; border-collapse: collapse; margin: 8px 0; }
    table.header-table td { padding: 4px 6px; font-size: 9.5px; border: 1px solid #c7d2dc; }
    table.header-table td.label { font-weight: bold; width: 90px; background: #eef2f4; }
    .section-title { background: #dcebf5; color: #123b70; font-weight: bold; padding: 4px 6px; font-size: 10px; margin-top: 8px; }
    table.grid { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
    table.grid th, table.grid td { border: 1px solid #9eacb9; padding: 2px 3px; font-size: 7.5px; text-align: center; }
    table.grid th { background: #dcebf5; color: #123b70; }
    table.grid td.skill { text-align: left; font-weight: bold; background: #edf5fa; white-space: nowrap; }
    .legend { width: 100%; border-collapse: collapse; margin-top: 6px; }
    .legend td { border: 1px solid #c7d2dc; padding: 4px; font-size: 9px; text-align: center; }
    .notes-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
    .notes-table td { border: 1px solid #c7d2dc; padding: 6px; font-size: 9px; width: 50%; vertical-align: top; }
</style>
</head>
<body>
    <h1>MBT DRIVERS RECORD</h1>
    <p class="muted">Skill progression record - generated {{ now()->format('d M Y H:i') }}</p>

    <table class="header-table">
        <tr>
            <td class="label">Student</td><td>{{ $student->full_name }}</td>
            <td class="label">Licence No.</td><td>{{ $student->licence_no ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Instructor</td><td>{{ $progress->data['header']['instructor'] ?? '-' }}</td>
            <td class="label">Vehicle Reg.</td><td>{{ $progress->data['header']['vehicle'] ?? '-' }}</td>
        </tr>
    </table>

    @php $rowCounter = 0; @endphp
    @foreach ($groupsList as [$groupName, $skills])
        <div class="section-title">{{ $groupName }}</div>
        <table class="grid">
            <tr>
                <th>Skill</th>
                @for ($lesson = 1; $lesson <= $lessons; $lesson++)
                    <th>L{{ $lesson }}<br>{{ $progress->data['dates'][$lesson] ?? '' }}</th>
                @endfor
            </tr>
            @foreach ($skills as $skillName)
                <tr>
                    <td class="skill">{{ $skillName }}</td>
                    @for ($lesson = 1; $lesson <= $lessons; $lesson++)
                        <td>{{ $progress->data['ratings']['r'.$rowCounter.'_'.$lesson] ?? '' }}</td>
                    @endfor
                </tr>
                @php $rowCounter++; @endphp
            @endforeach
        </table>
    @endforeach

    <table class="legend">
        <tr>
            <td><b>1</b> Introduce</td>
            <td><b>2</b> Under full instruction</td>
            <td><b>3</b> Prompted</td>
            <td><b>4</b> Seldom prompted</td>
            <td><b>5</b> Independent</td>
        </tr>
    </table>

    <table class="notes-table">
        <tr>
            <td><b>Focus areas</b><br>{{ $progress->data['focus'] ?? '-' }}</td>
            <td><b>Comments</b><br>{{ $progress->data['comments'] ?? '-' }}</td>
        </tr>
    </table>
</body>
</html>
