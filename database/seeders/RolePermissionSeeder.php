<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed the application's roles, permissions and the initial admin user.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage-users',
            'manage-roles',
            'manage-students',
            'view-students',
            'fill-assessment-form',
            'fill-progress-record',
            'fill-reflection-form',
            'view-all-submissions',
            'download-pdf',
            'delete-submission',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $admin = Role::findOrCreate('admin', 'web');
        $admin->syncPermissions($permissions);

        $teacher = Role::findOrCreate('teacher', 'web');
        $teacher->syncPermissions([
            'view-students',
            'manage-students',
            'fill-assessment-form',
            'fill-progress-record',
            'fill-reflection-form',
            'view-all-submissions',
            'download-pdf',
        ]);

        $adminUser = User::updateOrCreate(
            ['email' => 'pbidari46@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Password123!'),
                'is_active' => true,
                'must_change_password' => true,
                'email_verified_at' => now(),
            ]
        );

        if (! $adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }
    }
}
