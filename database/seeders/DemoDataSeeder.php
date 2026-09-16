<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed a demo teacher account and a few sample students for smoke-testing.
     */
    public function run(): void
    {
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@drivingtest.test'],
            [
                'name' => 'Sam Teacher',
                'password' => Hash::make('Password123!'),
                'is_active' => true,
                'must_change_password' => false,
                'email_verified_at' => now(),
            ]
        );

        if (! $teacher->hasRole('teacher')) {
            $teacher->assignRole('teacher');
        }

        $students = [
            ['full_name' => 'Alex Morgan', 'licence_no' => 'DL-10023', 'phone' => '555-0101', 'vehicle_reg' => 'REG-101'],
            ['full_name' => 'Priya Sharma', 'licence_no' => 'DL-10045', 'phone' => '555-0102', 'vehicle_reg' => 'REG-102'],
            ['full_name' => 'Jordan Lee', 'licence_no' => 'DL-10078', 'phone' => '555-0103', 'vehicle_reg' => 'REG-103'],
        ];

        foreach ($students as $data) {
            Student::updateOrCreate(
                ['licence_no' => $data['licence_no']],
                $data + ['created_by' => $teacher->id]
            );
        }
    }
}
