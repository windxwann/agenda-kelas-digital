<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Attendance;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create permissions if not exists
        $permissions = [
            'manage users', 'manage classes', 'manage teachers', 'manage students',
            'manage subjects', 'manage schedule', 'manage agenda', 'manage attendance',
            'view dashboard', 'view reports', 'export data'
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Create roles if not exists
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $studentRole = Role::firstOrCreate(['name' => 'siswa']);
        $sekretarisRole = Role::firstOrCreate(['name' => 'sekretaris']);
        Role::firstOrCreate(['name' => 'wali_kelas']);
        Role::firstOrCreate(['name' => 'wakasek_kurikulum']);
        
        // Assign permissions to roles
        $superAdminRole->syncPermissions(Permission::all());
        $adminRole->syncPermissions(['manage classes', 'manage teachers', 'manage students', 'manage subjects', 'manage schedule', 'view dashboard']);
        $teacherRole->syncPermissions(['manage agenda', 'manage attendance', 'view dashboard', 'view reports']);
        $studentRole->syncPermissions(['view dashboard']);
        
        // Create/Update Super Admin User
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@school.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');
        
        // Create/Update Teacher
        $teacher = User::updateOrCreate(
            ['email' => 'guru@school.com'],
            [
                'name' => 'Ramadhan, S.Pd',
                'password' => Hash::make('password'),
                'nip' => '198001012010011001',
                'phone' => '081234567890',
                'email_verified_at' => now(),
            ]
        );
        $teacher->assignRole('teacher');

        // Get or Create Classes based on user request
        $class1 = Classes::firstOrCreate(
            ['name' => 'XI RPL 1'],
            [
                'grade_level' => 'XI',
                'academic_year' => '2024/2025',
                'homeroom_teacher_id' => $teacher->id,
                'capacity' => 36,
                'is_active' => true,
            ]
        );
        
        $class2 = Classes::firstOrCreate(
            ['name' => 'XI RPL 2'],
            [
                'grade_level' => 'XI',
                'academic_year' => '2024/2025',
                'homeroom_teacher_id' => $teacher->id,
                'capacity' => 36,
                'is_active' => true,
            ]
        );

        // Create/Update Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name' => 'Admin Sekolah',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');
        
        // Create Sekretaris 1 (RPL 1)
        $sekretaris1 = User::updateOrCreate(
            ['email' => 'sekretaris@school.com'],
            [
                'name' => 'Sekretaris RPL 1',
                'password' => Hash::make('password'),
                'class_id' => $class1->id,
                'email_verified_at' => now(),
                'nis' => '2024002',
                'nisn' => '0098765433',
                'gender' => 'P',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2008-11-20',
                'address' => 'Jl. Asia Afrika No. 22',
                'rt' => '02',
                'rw' => '01',
                'kelurahan' => 'Kebon Pisang',
                'kecamatan' => 'Sumur Bandung',
                'phone' => '081234567892',
            ]
        );
        $sekretaris1->assignRole('sekretaris');
        $sekretaris1->assignRole('siswa');

        // Create Sekretaris 2 (RPL 2)
        $sekretaris2 = User::updateOrCreate(
            ['email' => 'sekretaris2@school.com'],
            [
                'name' => 'Sekretaris RPL 2',
                'password' => Hash::make('password'),
                'class_id' => $class2->id,
                'email_verified_at' => now(),
                'nis' => '2024003',
                'nisn' => '0098765434',
                'gender' => 'L',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2008-03-12',
                'address' => 'Jl. Braga No. 5',
                'rt' => '01',
                'rw' => '04',
                'kelurahan' => 'Braga',
                'kecamatan' => 'Sumur Bandung',
                'phone' => '081234567893',
            ]
        );
        $sekretaris2->assignRole('sekretaris');
        $sekretaris2->assignRole('siswa');
        
        // Create Student for RPL 1
        $student = User::updateOrCreate(
            ['email' => 'siswa@school.com'],
            [
                'name' => 'Ahmad Wijaya',
                'password' => Hash::make('password'),
                'nis' => '2024001',
                'nisn' => '0098765432',
                'gender' => 'L',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2008-05-15',
                'address' => 'Jl. Merdeka No. 10',
                'rt' => '03',
                'rw' => '05',
                'kelurahan' => 'Babakan Ciamis',
                'kecamatan' => 'Sumur Bandung',
                'phone' => '081234567891',
                'class_id' => $class1->id,
                'email_verified_at' => now(),
            ]
        );
        $student->assignRole('siswa');

        // Create Wakasek Kurikulum User
        $wakasek = User::updateOrCreate(
            ['email' => 'wakasek@school.com'],
            [
                'name' => 'Wakasek Kurikulum',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $wakasek->assignRole('wakasek_kurikulum');

        // Ensure teacher has wali_kelas role since they are homeroom for RPL 1 & 2
        $teacher->assignRole('wali_kelas');
        
        // Create Subjects
        $subject1 = Subject::firstOrCreate(['name' => 'Pemrograman Web'], ['code' => 'MAPEL-01', 'teacher_id' => $teacher->id]);
        $subject2 = Subject::firstOrCreate(['name' => 'Basis Data'], ['code' => 'MAPEL-02', 'teacher_id' => $teacher->id]);

        // Create Schedules for Teacher
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        foreach ($days as $day) {
            Schedule::firstOrCreate(
                [
                    'class_id' => $class1->id,
                    'subject_id' => $subject1->id,
                    'day' => $day,
                    'start_time' => '07:30:00',
                ],
                [
                    'teacher_id' => $teacher->id,
                    'end_time' => '09:00:00',
                    'room' => 'Lab Komp 1'
                ]
            );
            
            Schedule::firstOrCreate(
                [
                    'class_id' => $class2->id,
                    'subject_id' => $subject2->id,
                    'day' => $day,
                    'start_time' => '10:00:00',
                ],
                [
                    'teacher_id' => $teacher->id,
                    'end_time' => '11:30:00',
                    'room' => 'Lab Komp 2'
                ]
            );
        }

        $this->call(AcademicYearSeeder::class);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Super Admin: superadmin@school.com / password');
        $this->command->info('Admin: admin@school.com / password');
        $this->command->info('Wakasek Kurikulum: wakasek@school.com / password');
        $this->command->info('Wali Kelas / Teacher: guru@school.com / password');
        $this->command->info('Sekretaris 1 (RPL 1): sekretaris@school.com / password');
        $this->command->info('Sekretaris 2 (RPL 2): sekretaris2@school.com / password');
        $this->command->info('Teacher: guru@school.com / password');
        $this->command->info('Student: siswa@school.com / password');
    }
}