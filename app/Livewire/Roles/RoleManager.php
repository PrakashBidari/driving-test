<?php

namespace App\Livewire\Roles;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleManager extends Component
{
    public bool $showRoleModal = false;

    public ?int $editingRoleId = null;

    public string $roleName = '';

    public array $rolePermissions = [];

    public bool $showPermissionModal = false;

    public string $newPermissionName = '';

    public function createRole(): void
    {
        $this->editingRoleId = null;
        $this->roleName = '';
        $this->rolePermissions = [];
        $this->showRoleModal = true;
    }

    public function editRole(int $roleId): void
    {
        $role = Role::findOrFail($roleId);
        $this->editingRoleId = $role->id;
        $this->roleName = $role->name;
        $this->rolePermissions = $role->permissions->pluck('name')->toArray();
        $this->showRoleModal = true;
    }

    public function saveRole(): void
    {
        $data = $this->validate([
            'roleName' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($this->editingRoleId)],
        ]);

        $role = $this->editingRoleId
            ? Role::findOrFail($this->editingRoleId)
            : Role::create(['name' => $data['roleName'], 'guard_name' => 'web']);

        if ($this->editingRoleId) {
            $role->update(['name' => $data['roleName']]);
        }

        $role->syncPermissions($this->rolePermissions);

        session()->flash('success', 'Role saved successfully.');
        $this->showRoleModal = false;
    }

    public function deleteRole(int $roleId): void
    {
        $role = Role::findOrFail($roleId);

        if (in_array($role->name, ['admin', 'teacher'], true)) {
            session()->flash('success', 'The built-in admin/teacher roles cannot be deleted.');

            return;
        }

        $role->delete();
        session()->flash('success', 'Role deleted.');
    }

    public function createPermission(): void
    {
        $this->newPermissionName = '';
        $this->showPermissionModal = true;
    }

    public function savePermission(): void
    {
        $data = $this->validate([
            'newPermissionName' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create(['name' => $data['newPermissionName'], 'guard_name' => 'web']);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        session()->flash('success', 'Permission created.');
        $this->showPermissionModal = false;
    }

    public function render()
    {
        return view('livewire.roles.role-manager', [
            'roles' => Role::with('permissions')->orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ])->layout('layouts.app', ['title' => 'Roles & Permissions']);
    }
}
