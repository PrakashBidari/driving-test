<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-brand-50 antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">
        <div class="mb-8 flex flex-col items-center gap-2 text-center">
            <span class="text-4xl">🚗</span>
            <h1 class="text-xl font-bold text-brand-700">{{ config('app.name') }}</h1>
            <p class="text-sm text-slate-500">Driving skill assessment &amp; progress platform</p>
        </div>

        <div class="w-full max-w-md rounded-xl border border-slate-200 bg-white p-6 shadow-lg sm:p-8">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html>
