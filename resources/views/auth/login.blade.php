<x-layouts.guest title="Log in">
    <h2 class="mb-6 text-lg font-semibold text-slate-800">Log in to your account</h2>

    @if (session('status'))
        <div class="mb-4 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400">
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400">
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-400">
            Remember me
        </label>

        <button type="submit" class="w-full rounded-md bg-brand-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-600">
            Log in
        </button>

        <div class="text-center text-sm">
            <a href="{{ route('password.request') }}" class="text-brand-700 hover:underline">Forgot your password?</a>
        </div>
    </form>
</x-layouts.guest>
