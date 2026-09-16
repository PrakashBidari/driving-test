<div>
    <div class="mb-4 flex items-center justify-between gap-3">
        <h1 class="text-lg font-semibold text-slate-800">Roles &amp; Permissions</h1>
        <div class="flex items-center gap-2">
            <button wire:click="createPermission" class="rounded-md bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">+ New permission</button>
            <button wire:click="createRole" class="rounded-md bg-brand-700 px-3 py-2 text-sm font-semibold text-white hover:bg-brand-600">+ New role</button>
        </div>
    </div>

    <div class="space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($roles as $role)
                <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-semibold capitalize text-slate-800">{{ $role->name }}</h3>
                        <div class="flex shrink-0 gap-2">
                            <button wire:click="editRole({{ $role->id }})" class="text-xs font-medium text-brand-700 hover:underline">Edit</button>
                            @unless (in_array($role->name, ['admin', 'teacher']))
                                <button wire:click="deleteRole({{ $role->id }})" wire:confirm="Delete this role?" class="text-xs font-medium text-red-600 hover:underline">Delete</button>
                            @endunless
                        </div>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-1.5">
                        @forelse ($role->permissions as $permission)
                            <span class="rounded-full bg-brand-50 px-2 py-0.5 text-xs text-brand-700">{{ $permission->name }}</span>
                        @empty
                            <span class="text-xs text-slate-400">No permissions assigned.</span>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <h3 class="mb-2 font-semibold text-slate-800">All permissions</h3>
            <div class="flex flex-wrap gap-1.5">
                @foreach ($permissions as $permission)
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600">{{ $permission->name }}</span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Role modal --}}
    @if ($showRoleModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="max-h-[90vh] w-full max-w-md overflow-y-auto rounded-lg bg-white shadow-xl">
                <form wire:submit="saveRole">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <h3 class="font-semibold text-slate-800">{{ $editingRoleId ? 'Edit role' : 'New role' }}</h3>
                        <button type="button" wire:click="$set('showRoleModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>
                    <div class="space-y-4 px-5 py-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Role name *</label>
                            <input wire:model="roleName" type="text" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                            @error('roleName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Permissions</label>
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                @foreach ($permissions as $permission)
                                    <label class="flex items-center gap-2 text-sm text-slate-600">
                                        <input type="checkbox" wire:model="rolePermissions" value="{{ $permission->name }}" class="rounded border-slate-300 text-brand-600">
                                        {{ $permission->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <button type="button" wire:click="$set('showRoleModal', false)" class="rounded-md bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="rounded-md bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">Save role</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Permission modal --}}
    @if ($showPermissionModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-sm rounded-lg bg-white shadow-xl">
                <form wire:submit="savePermission">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <h3 class="font-semibold text-slate-800">New permission</h3>
                        <button type="button" wire:click="$set('showPermissionModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>
                    <div class="space-y-4 px-5 py-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Permission name *</label>
                            <input wire:model="newPermissionName" type="text" placeholder="e.g. export-reports" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                            @error('newPermissionName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <button type="button" wire:click="$set('showPermissionModal', false)" class="rounded-md bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="rounded-md bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">Create</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
