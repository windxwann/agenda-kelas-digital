<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classes;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create permissions
        $permissions = [
            'manage users',
            'manage classes',
            'manage teachers', 
            'manage students',
            'manage subjects',
            'manage schedule',
            'manage agenda',
            'manage attendance',
            'view dashboard',
            'view reports',
            'export data'
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
        // Create roles
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $adminRole = Role::create(['name' => 'admin']);
        $teacherRole = Role::create(['name' => 'teacher']);
        $studentRole = Role::create(['name' => 'siswa']);
        Role::create(['name' => 'sekretaris']);
        Role::create(['name' => 'wali_kelas']);
        Role::create(['name' => 'wakasek_kurikulum']);
        
        // Assign permissions to roles
        $superAdminRole->givePermissionTo(Permission::all());
        $adminRole->givePermissionTo(['manage classes', 'manage teachers', 'manage students', 'manage subjects', 'manage schedule', 'view dashboard']);
        $teacherRole->givePermissionTo(['manage agenda', 'manage attendance', 'view dashboard', 'view reports']);
        $studentRole->givePermissionTo(['view dashboard']);
        
        // Create Super Admin User
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@school.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super_admin');
        
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');
        
        // Create Teacher
        $teacher = User::create([
            'name' => 'Budi Santoso, S.Pd',
            'email' => 'budi@school.com',
            'password' => Hash::make('password'),
            'nip' => '198001012010011001',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);
        $teacher->assignRole('teacher');
        
        // Create Class
        $class = Classes::create([
            'name' => 'XII IPA 1',
            'grade_level' => 'XII',
            'academic_year' => '2024/2025',
            'homeroom_teacher_id' => $teacher->id,
            'capacity' => 36,
            'description' => 'Kelas unggulan IPA',
            'is_active' => true,
        ]);
        
        // Create Student
        $student = User::create([
            'name' => 'Ahmad Wijaya',
            'email' => 'ahmad@student.com',
            'password' => Hash::make('password'),
            'nis' => '2024001',
            'phone' => '081234567891',
            'class_id' => $class->id,
            'email_verified_at' => now(),
        ]);
        $student->assignRole('siswa');
        
        $this->command->info('Database seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Super Admin: superadmin@school.com / password');
        $this->command->info('Admin: admin@school.com / password');
        $this->command->info('Teacher: budi@school.com / password');
        $this->command->info('Student: ahmad@student.com / password');
    }
}