<x-layouts.guest title="Forgot password">
    <h2 class="mb-2 text-lg font-semibold text-slate-800">Forgot your password?</h2>
    <p class="mb-6 text-sm text-slate-500">Enter your email and we'll send you a password reset link.</p>

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

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400">
        </div>

        <button type="submit" class="w-full rounded-md bg-brand-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-600">
            Email password reset link
        </button>

        <div class="text-center text-sm">
            <a href="{{ route('login') }}" class="text-brand-700 hover:underline">Back to login</a>
        </div>
    </form>
</x-layouts.guest>
