<div>
    <div class="mb-4 flex items-center justify-between gap-3">
        <h1 class="text-lg font-semibold text-slate-800">Users</h1>
        <button wire:click="createUser" class="rounded-md bg-brand-700 px-3 py-2 text-sm font-semibold text-white hover:bg-brand-600">+ Add user</button>
    </div>

    <div class="space-y-3">
        {{-- Desktop table --}}
        <div class="hidden overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm lg:block">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $user->name }} @if ($user->id === auth()->id()) <span class="text-xs text-slate-400">(you)</span> @endif</td>
                            <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-slate-600 capitalize">{{ $user->roles->pluck('name')->implode(', ') ?: '—' }}</td>
                            <td class="px-4 py-3">
                                <span @class(['rounded-full px-2 py-0.5 text-xs font-semibold', 'bg-green-100 text-green-700' => $user->is_active, 'bg-slate-100 text-slate-500' => ! $user->is_active])>
                                    {{ $user->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="editUser({{ $user->id }})" class="rounded-md bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200">Edit</button>
                                    @if ($user->id !== auth()->id())
                                        <button wire:click="toggleActive({{ $user->id }})" class="rounded-md bg-amber-50 px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-100">
                                            {{ $user->is_active ? 'Disable' : 'Enable' }}
                                        </button>
                                        <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Remove this user?" class="rounded-md bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100">Delete</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="space-y-3 lg:hidden">
            @foreach ($users as $user)
                <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800">{{ $user->name }} @if ($user->id === auth()->id()) <span class="text-xs text-slate-400">(you)</span> @endif</p>
                            <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                        </div>
                        <span @class(['shrink-0 rounded-full px-2 py-0.5 text-xs font-semibold', 'bg-green-100 text-green-700' => $user->is_active, 'bg-slate-100 text-slate-500' => ! $user->is_active])>
                            {{ $user->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </div>
                    <p class="mt-2 text-xs capitalize text-slate-600">Role: {{ $user->roles->pluck('name')->implode(', ') ?: '—' }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <button wire:click="editUser({{ $user->id }})" class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700">Edit</button>
                        @if ($user->id !== auth()->id())
                            <button wire:click="toggleActive({{ $user->id }})" class="rounded-md bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700">
                                {{ $user->is_active ? 'Disable' : 'Enable' }}
                            </button>
                            <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Remove this user?" class="rounded-md bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700">Delete</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-lg bg-white shadow-xl">
                <form wire:submit="save">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <h3 class="font-semibold text-slate-800">{{ $editingId ? 'Edit user' : 'Add user' }}</h3>
                        <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>

                    <div class="space-y-4 px-5 py-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Name *</label>
                            <input wire:model="name" type="text" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Email *</label>
                            <input wire:model="email" type="email" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                {{ $editingId ? 'New password (leave blank to keep current)' : 'Password *' }}
                            </label>
                            <input wire:model="password" type="password" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Role *</label>
                            <select wire:model="role" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm capitalize">
                                @foreach ($roles as $r)
                                    <option value="{{ $r->name }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Extra individual permissions</label>
                            <p class="mb-2 text-xs text-slate-400">On top of the permissions granted by the role above.</p>
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                @foreach ($permissions as $permission)
                                    <label class="flex items-center gap-2 text-sm text-slate-600">
                                        <input type="checkbox" wire:model="extraPermissions" value="{{ $permission->name }}" class="rounded border-slate-300 text-brand-600">
                                        {{ $permission->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        @if ($editingId)
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-brand-600">
                            Active (can log in)
                        </label>
                        @endif
                    </div>

                    <div class="flex justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <button type="button" wire:click="closeModal" class="rounded-md bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="rounded-md bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">
                            {{ $editingId ? 'Save changes' : 'Create user' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
