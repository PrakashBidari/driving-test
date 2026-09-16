<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_teacher_can_view_students_but_not_manage_users_or_roles(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)->get('/students')->assertOk();
        $this->actingAs($teacher)->get('/users')->assertForbidden();
        $this->actingAs($teacher)->get('/roles')->assertForbidden();
    }

    public function test_admin_can_manage_users_and_roles(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->get('/users')->assertOk();
        $this->actingAs($admin)->get('/roles')->assertOk();
    }

    public function test_guest_is_redirected_from_protected_pages(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/students')->assertRedirect('/login');
    }
}
