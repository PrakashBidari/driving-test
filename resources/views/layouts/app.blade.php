<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $title ?? config('app.name') }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/4.1.7/signature_pad.umd.min.js"></script>
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col bg-brand-700 text-white transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex items-center gap-2 border-b border-white/10 px-5 py-4">
                <span class="text-2xl">🚗</span>
                <div class="leading-tight">
                    <p class="font-bold">{{ config('app.name') }}</p>
                    <p class="text-xs text-brand-100/80">Test Center Dashboard</p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4 text-sm">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <x-slot:icon>🏠</x-slot:icon>
                    Dashboard
                </x-nav-link>

                @can('view-students')
                <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                    <x-slot:icon>🎓</x-slot:icon>
                    Students
                </x-nav-link>
                @endcan

                @can('manage-users')
                <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    <x-slot:icon>👤</x-slot:icon>
                    Users
                </x-nav-link>
                @endcan

                @can('manage-roles')
                <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">
                    <x-slot:icon>🔐</x-slot:icon>
                    Roles &amp; Permissions
                </x-nav-link>
                @endcan

                <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                    <x-slot:icon>⚙️</x-slot:icon>
                    My Profile
                </x-nav-link>
            </nav>

            <div class="border-t border-white/10 p-4">
                <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs text-brand-100/70">{{ auth()->user()->getRoleNames()->implode(', ') ?: 'No role' }}</p>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full rounded-md bg-white/10 px-3 py-2 text-sm font-medium transition hover:bg-white/20">
                        Log out
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main column --}}
        <div class="flex min-w-0 flex-1 flex-col">
            {{-- Topbar --}}
            <header class="sticky top-0 z-20 flex items-center gap-3 border-b border-slate-200 bg-white px-4 py-3" style="padding-top: max(0.75rem, env(safe-area-inset-top))">
                <button @click="sidebarOpen = true" class="rounded-md p-2 text-slate-600 hover:bg-slate-100 lg:hidden" aria-label="Open menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="min-w-0 flex-1 truncate text-base font-semibold text-slate-800 sm:text-lg">{{ $title ?? 'Dashboard' }}</h1>
            </header>

            <main class="min-w-0 flex-1 px-3 py-4 sm:px-6 sm:py-6">
                @if (session('success'))
                    <div class="mb-4 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
