<x-layouts.app title="My Profile">
    <div class="mx-auto max-w-2xl space-y-6">
        @if (auth()->user()->must_change_password)
            <div class="rounded-md bg-amber-50 px-4 py-3 text-sm text-amber-800">
                ⚠ Please change your temporary password below.
            </div>
        @endif

        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-semibold text-slate-800">Profile information</h2>

            @if (session('status') === 'profile-information-updated')
                <div class="mb-4 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">Profile updated successfully.</div>
            @endif

            <form method="POST" action="{{ route('user-profile-information.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="rounded-md bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">Save</button>
            </form>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="mb-4 font-semibold text-slate-800">Update password</h2>

            @if (session('status') === 'password-updated')
                <div class="mb-4 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">Password updated successfully.</div>
            @endif

            @if ($errors->updatePassword->any())
                <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->updatePassword->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('user-password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Current password</label>
                    <input type="password" name="current_password" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">New password</label>
                    <input type="password" name="password" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Confirm new password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>

                <button type="submit" class="rounded-md bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">Update password</button>
            </form>
        </div>
    </div>
</x-layouts.app>
