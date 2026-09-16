<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    public bool $showModal = false;

    public ?int $editingId = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    public string $password = '';

    #[Validate('required|exists:roles,name')]
    public string $role = 'teacher';

    public array $extraPermissions = [];

    public bool $is_active = true;

    public function createUser(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function editUser(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->getRoleNames()->first() ?? 'teacher';
        $this->extraPermissions = $user->getDirectPermissions()->pluck('name')->toArray();
        $this->is_active = $user->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'role' => 'required|exists:roles,name',
        ];

        $rules['password'] = $this->editingId ? 'nullable|min:8' : 'required|min:8';

        $data = $this->validate($rules);

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'is_active' => $this->is_active,
            ]);

            if (! empty($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }

            session()->flash('success', 'User updated successfully.');
        } else {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_active' => true,
                'must_change_password' => true,
                'email_verified_at' => now(),
            ]);

            session()->flash('success', 'User created successfully.');
        }

        $user->syncRoles([$data['role']]);
        $user->syncPermissions($this->extraPermissions);

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleActive(int $userId): void
    {
        if ($userId === auth()->id()) {
            return;
        }

        $user = User::findOrFail($userId);
        $user->update(['is_active' => ! $user->is_active]);
    }

    public function deleteUser(int $userId): void
    {
        if ($userId === auth()->id()) {
            return;
        }

        User::findOrFail($userId)->delete();
        session()->flash('success', 'User removed.');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'teacher';
        $this->extraPermissions = [];
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.users.user-manager', [
            'users' => User::with('roles')->orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ])->layout('layouts.app', ['title' => 'Users']);
    }
}
