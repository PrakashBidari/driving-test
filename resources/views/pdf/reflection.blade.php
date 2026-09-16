<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; color: #182536; }
    h1 { font-size: 16px; color: #123b70; margin: 0 0 2px; }
    .muted { color: #667685; font-size: 10px; }
    table.header-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    table.header-table td { padding: 4px 6px; font-size: 10px; border: 1px solid #c7d2dc; }
    table.header-table td.label { font-weight: bold; width: 110px; background: #e8f0f7; }
    .section-title { background: #e8f0f7; color: #123b70; font-weight: bold; padding: 5px 6px; font-size: 11px; margin-top: 10px; }
    .content-box { border: 1px solid #c7d2dc; padding: 8px; font-size: 10px; min-height: 40px; }
    .scale-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
    .scale-table td { border: 1px solid #c7d2dc; text-align: center; padding: 4px; font-size: 9px; }
    .scale-table td.active { background: #123b70; color: #fff; font-weight: bold; }
</style>
</head>
<body>
    <h1>Driving Lesson Reflection Notes</h1>
    <p class="muted">Generated {{ now()->format('d M Y H:i') }}</p>

    <table class="header-table">
        <tr>
            <td class="label">Name</td><td>{{ $student->full_name }}</td>
            <td class="label">Date</td><td>{{ $reflection->lesson_date?->format('d M Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Instructor</td><td>{{ $reflection->data['instructor'] ?? '-' }}</td>
            <td class="label">Next lesson</td><td>{{ $reflection->data['next_date'] ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">1. What went well today?</div>
    <div class="content-box">{{ $reflection->data['went_well'] ?? '-' }}</div>

    <div class="section-title">2. What could we do differently?</div>
    <div class="content-box">{{ $reflection->data['differently'] ?? '-' }}</div>

    <div class="section-title">3. Current driving standard (0-10)</div>
    <table class="scale-table">
        <tr>
            @for ($i = 0; $i <= 10; $i++)
                <td class="{{ $reflection->scale === $i ? 'active' : '' }}">{{ $i }}</td>
            @endfor
        </tr>
    </table>

    <div class="section-title">4. Goals for next lesson</div>
    <div class="content-box">{{ $reflection->data['goals'] ?? '-' }}</div>
</body>
</html>
