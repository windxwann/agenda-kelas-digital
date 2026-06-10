<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SearchController;

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Manajemen Kelas
    Route::resource('classes', ClassController::class);
    
    // Manajemen Tahun Ajaran
    Route::resource('academic-years', \App\Http\Controllers\Admin\AcademicYearController::class)->except(['create', 'show', 'edit']);
    Route::post('academic-years/{academic_year}/set-active', [\App\Http\Controllers\Admin\AcademicYearController::class, 'setActive'])->name('academic-years.set-active');
    
    // Kenaikan Kelas
    Route::get('class-promotions', [\App\Http\Controllers\Admin\ClassPromotionController::class, 'index'])->name('class-promotions.index');
    Route::get('class-promotions/preview', [\App\Http\Controllers\Admin\ClassPromotionController::class, 'preview'])->name('class-promotions.preview');
    Route::post('class-promotions/promote', [\App\Http\Controllers\Admin\ClassPromotionController::class, 'promote'])->name('class-promotions.promote');
    
    // Manajemen Siswa
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('students/export/template', [StudentController::class, 'exportTemplate'])->name('students.export.template');
    Route::get('students/bulk-graduation', [StudentController::class, 'bulkGraduation'])->name('students.bulk-graduation');
    Route::post('students/process-bulk-graduation', [StudentController::class, 'processBulkGraduation'])->name('students.process-bulk-graduation');
    Route::delete('students/bulk-delete', [StudentController::class, 'bulkDelete'])->name('students.bulk-delete');
    Route::resource('students', StudentController::class);
    
    // Manajemen Guru
    Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
    Route::get('teachers/export/template', [TeacherController::class, 'exportTemplate'])->name('teachers.export.template');
    Route::resource('teachers', TeacherController::class);
    
    // Manajemen Mata Pelajaran
    Route::resource('subjects', SubjectController::class);
    
    // Manajemen Jadwal
    Route::get('schedules/get-available-rooms', [ScheduleController::class, 'getAvailableRooms'])->name('schedules.get-available-rooms');
    Route::get('schedules/class/{class}', [ScheduleController::class, 'byClass'])->name('schedules.byClass');
    Route::resource('schedules', ScheduleController::class);
    
    // Manajemen Ruangan
    Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);

    // Monitoring
    Route::get('monitoring/rooms', [\App\Http\Controllers\Admin\MonitoringController::class, 'rooms'])->name('monitoring.rooms');
    Route::get('reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('reports/attendance/student/{id}', [ReportController::class, 'studentAttendance'])->name('reports.attendance.student');
    Route::get('reports/export/pdf', [ReportController::class, 'exportPDF'])->name('reports.export.pdf');
    Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    
    // Monitoring
    Route::get('monitoring/classes', [DashboardController::class, 'monitoringClasses'])->name('monitoring.classes');
    Route::get('monitoring/teachers', [DashboardController::class, 'monitoringTeachers'])->name('monitoring.teachers');
    
    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Profile & Settings
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

    // Notifications API
    Route::get('/api/notifications', function() {
        return response()->json([
            ['id' => 1, 'title' => 'Sistem Diperbarui', 'message' => 'Fitur real-time aktif.', 'time' => 'Baru saja'],
            ['id' => 2, 'title' => 'Agenda Baru', 'message' => 'Kelas XII IPA 1 menambahkan agenda.', 'time' => '5 menit yang lalu']
        ]);
    })->name('notifications.api');
});

// Admin Routes (Kepala Sekolah)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/api/stats', [DashboardController::class, 'statsApi'])->name('dashboard.api.stats');
    
    // Manajemen Kelas
    Route::resource('classes', ClassController::class);
    
    // Manajemen Tahun Ajaran
    Route::resource('academic-years', \App\Http\Controllers\Admin\AcademicYearController::class)->except(['create', 'show', 'edit']);
    Route::post('academic-years/{academic_year}/set-active', [\App\Http\Controllers\Admin\AcademicYearController::class, 'setActive'])->name('academic-years.set-active');
    
    // Kenaikan Kelas
    Route::get('class-promotions', [\App\Http\Controllers\Admin\ClassPromotionController::class, 'index'])->name('class-promotions.index');
    Route::get('class-promotions/preview', [\App\Http\Controllers\Admin\ClassPromotionController::class, 'preview'])->name('class-promotions.preview');
    Route::post('class-promotions/promote', [\App\Http\Controllers\Admin\ClassPromotionController::class, 'promote'])->name('class-promotions.promote');
    
    // Manajemen Siswa
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('students/export/template', [StudentController::class, 'exportTemplate'])->name('students.export.template');
    Route::get('students/bulk-graduation', [StudentController::class, 'bulkGraduation'])->name('students.bulk-graduation');
    Route::post('students/process-bulk-graduation', [StudentController::class, 'processBulkGraduation'])->name('students.process-bulk-graduation');
    Route::delete('students/bulk-delete', [StudentController::class, 'bulkDelete'])->name('students.bulk-delete');
    Route::resource('students', StudentController::class);
    
    // Manajemen Guru
    Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
    Route::get('teachers/export/template', [TeacherController::class, 'exportTemplate'])->name('teachers.export.template');
    Route::resource('teachers', TeacherController::class);
    
    // Manajemen Mata Pelajaran
    Route::resource('subjects', SubjectController::class);
    
    // Manajemen Jadwal
    Route::get('schedules/get-available-rooms', [ScheduleController::class, 'getAvailableRooms'])->name('schedules.get-available-rooms');
    Route::resource('schedules', ScheduleController::class);
    
    // Manajemen Ruangan
    Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);

    // Monitoring
    Route::get('monitoring/rooms', [\App\Http\Controllers\Admin\MonitoringController::class, 'rooms'])->name('monitoring.rooms');
    Route::get('reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('reports/attendance/student/{id}', [ReportController::class, 'studentAttendance'])->name('reports.attendance.student');
    Route::get('reports/export/pdf', [ReportController::class, 'exportPDF'])->name('reports.export.pdf');
    Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    
    // Monitoring
    Route::get('monitoring/classes', [DashboardController::class, 'monitoringClasses'])->name('monitoring.classes');
    
    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Profile & Settings
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

    // Notifications API
    Route::get('/api/notifications', function() {
        return response()->json([
            ['id' => 1, 'title' => 'Sistem Diperbarui', 'message' => 'Fitur real-time aktif.', 'time' => 'Baru saja'],
            ['id' => 2, 'title' => 'Agenda Baru', 'message' => 'Kelas XII IPA 1 menambahkan agenda.', 'time' => '5 menit yang lalu']
        ]);
    })->name('notifications.api');
});

// Sekretaris Routes
Route::middleware(['auth', 'role:sekretaris'])->prefix('sekretaris')->name('sekretaris.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Sekretaris\DashboardController::class, 'index'])->name('dashboard');
    
    // Agenda Kelas
    Route::get('/agenda/get-schedule-info', [App\Http\Controllers\Sekretaris\AgendaController::class, 'getScheduleInfo'])->name('agenda.get-schedule-info');
    Route::get('/agenda', [App\Http\Controllers\Sekretaris\AgendaController::class, 'index'])->name('agenda.index');
    // Arsip Agenda
    Route::get('/agenda/archive', [App\Http\Controllers\Sekretaris\ArchiveController::class, 'index'])->name('agenda.archive');
    Route::get('/agenda/create', [App\Http\Controllers\Sekretaris\AgendaController::class, 'create'])->name('agenda.create');
    Route::post('/agenda', [App\Http\Controllers\Sekretaris\AgendaController::class, 'store'])->name('agenda.store');
    Route::get('/agenda/{agenda}/edit', [App\Http\Controllers\Sekretaris\AgendaController::class, 'edit'])->name('agenda.edit');
    Route::put('/agenda/{agenda}', [App\Http\Controllers\Sekretaris\AgendaController::class, 'update'])->name('agenda.update');
    Route::delete('/agenda/{agenda}', [App\Http\Controllers\Sekretaris\AgendaController::class, 'destroy'])->name('agenda.destroy');
    Route::get('/agenda/{id}/preview', [App\Http\Controllers\Sekretaris\AgendaController::class, 'preview'])->name('agenda.preview');
    
    // Presensi Siswa
    Route::get('/attendance', [App\Http\Controllers\Sekretaris\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/store', function() { return redirect()->route('sekretaris.attendance.index'); });
    Route::post('/attendance/store', [App\Http\Controllers\Sekretaris\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/report', [App\Http\Controllers\Sekretaris\AttendanceController::class, 'report'])->name('attendance.report');
    Route::get('/attendance/report/student/{id}', [App\Http\Controllers\Sekretaris\AttendanceController::class, 'studentAttendance'])->name('attendance.report.student');
    
    // Cetak Laporan
    Route::get('/print/agenda', [App\Http\Controllers\Sekretaris\PrintController::class, 'printAgenda'])->name('print.agenda');
    Route::get('/print/attendance', [App\Http\Controllers\Sekretaris\PrintController::class, 'printAttendance'])->name('print.attendance');
});

// Wali Kelas Routes
Route::middleware(['auth', 'role:wali_kelas'])->prefix('wali-kelas')->name('wali-kelas.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\WaliKelas\DashboardController::class, 'index'])->name('dashboard');
    
    // Monitoring Presensi
    Route::get('/attendance', [App\Http\Controllers\WaliKelas\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/report', [App\Http\Controllers\WaliKelas\AttendanceController::class, 'report'])->name('attendance.report');
    Route::get('/attendance/report/student/{id}', [App\Http\Controllers\WaliKelas\AttendanceController::class, 'studentAttendance'])->name('attendance.report.student');
    Route::get('/attendance/chart', [App\Http\Controllers\WaliKelas\AttendanceController::class, 'chart'])->name('attendance.chart');
    
    // Agenda Kelas (Read Only)
    Route::get('/agenda', [App\Http\Controllers\WaliKelas\AgendaController::class, 'index'])->name('agenda.index');
    Route::get('/agenda/{agenda}', [App\Http\Controllers\WaliKelas\AgendaController::class, 'show'])->name('agenda.show');
    
    // Monitoring Jurnal Guru
    Route::get('/journal', [App\Http\Controllers\WaliKelas\JournalController::class, 'index'])->name('journal.index');
    
    // Export Laporan
    Route::get('/export/attendance', [App\Http\Controllers\WaliKelas\ExportController::class, 'exportAttendance'])->name('export.attendance');
    Route::get('/export/attendance/pdf', [App\Http\Controllers\WaliKelas\ExportController::class, 'exportAttendancePDF'])->name('export.attendance.pdf');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Guru\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [App\Http\Controllers\Guru\ProfileController::class, 'update'])->name('profile.update');
});

// Wakasek Kurikulum Routes
Route::middleware(['auth', 'role:wakasek_kurikulum|super_admin'])->prefix('wakasek-kurikulum')->name('wakasek-kurikulum.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Wakasek\DashboardController::class, 'index'])->name('dashboard');
    
    // Monitoring Kurikulum
    Route::get('/curriculum', [App\Http\Controllers\Wakasek\CurriculumController::class, 'index'])->name('curriculum.index');
    Route::get('/curriculum/progress', [App\Http\Controllers\Wakasek\CurriculumController::class, 'progress'])->name('curriculum.progress');
    
    // Monitoring Pembelajaran
    Route::get('/teaching', [App\Http\Controllers\Wakasek\TeachingController::class, 'index'])->name('teaching.index');
    Route::get('/teaching/{teacher}', [App\Http\Controllers\Wakasek\TeachingController::class, 'show'])->name('teaching.show');
    
    // Evaluasi
    Route::get('/evaluation', [App\Http\Controllers\Wakasek\EvaluationController::class, 'index'])->name('evaluation.index');
    Route::get('/evaluation/report', [App\Http\Controllers\Wakasek\EvaluationController::class, 'report'])->name('evaluation.report');
    
    // Export Data
    Route::get('/export/teaching', [App\Http\Controllers\Wakasek\ExportController::class, 'exportTeaching'])->name('export.teaching');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Guru\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [App\Http\Controllers\Guru\ProfileController::class, 'update'])->name('profile.update');
});

// Siswa Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');
    
    // Agenda Kelas
    Route::get('/agenda', [App\Http\Controllers\Siswa\AgendaController::class, 'index'])->name('agenda.index');
    Route::get('/agenda/{id}/json', [App\Http\Controllers\Siswa\AgendaController::class, 'showJson'])->name('agenda.json');
    
    // Jadwal Pelajaran
    Route::get('/schedule', [App\Http\Controllers\Siswa\ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/by-date', [App\Http\Controllers\Siswa\ScheduleController::class, 'byDate'])->name('schedule.by-date');
    Route::get('/schedule/change-week', [App\Http\Controllers\Siswa\ScheduleController::class, 'changeWeek'])->name('schedule.change-week');
    Route::get('/schedule/today-date', [App\Http\Controllers\Siswa\ScheduleController::class, 'todayDate'])->name('schedule.today-date');
    
    // Presensi Pribadi
    Route::get('/attendance', [App\Http\Controllers\Siswa\AttendanceController::class, 'index'])->name('attendance.index');
});

// Guru Routes
Route::middleware(['auth', 'role:teacher'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('dashboard');
    
    // Presensi
    Route::get('/attendance', [App\Http\Controllers\Guru\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [App\Http\Controllers\Guru\AttendanceController::class, 'store'])->name('attendance.store');
    
    // Agenda / Jurnal Mengajar
    Route::get('/agenda/get-schedule-info', [App\Http\Controllers\Guru\AgendaController::class, 'getScheduleInfo'])->name('agenda.get-schedule-info');
    Route::get('/agenda', [App\Http\Controllers\Guru\AgendaController::class, 'index'])->name('agenda.index');
    Route::get('/agenda/create', [App\Http\Controllers\Guru\AgendaController::class, 'create'])->name('agenda.create');
    Route::post('/agenda', [App\Http\Controllers\Guru\AgendaController::class, 'store'])->name('agenda.store');
    Route::get('/agenda/{agenda}', [App\Http\Controllers\Guru\AgendaController::class, 'show'])->name('agenda.show');

    // Jurnal Guru (Alias for convenience if needed, but agenda is the primary term)
    Route::get('/journal', [App\Http\Controllers\Guru\AgendaController::class, 'index'])->name('journal.index');

    // Laporan
    Route::get('/report', [App\Http\Controllers\Guru\ReportController::class, 'index'])->name('report.index');
    Route::get('/report/export', [App\Http\Controllers\Guru\ReportController::class, 'export'])->name('report.export');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Guru\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [App\Http\Controllers\Guru\ProfileController::class, 'update'])->name('profile.update');
});

// Route untuk user yang sudah login tapi role tidak terdeteksi
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->hasRole('super_admin')) {
        return redirect()->route('super-admin.dashboard');
    } elseif ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('sekretaris')) {
        return redirect()->route('sekretaris.dashboard');
    } elseif ($user->hasRole('wali_kelas')) {
        return redirect()->route('wali-kelas.dashboard');
    } elseif ($user->hasRole('wakasek_kurikulum')) {
        return redirect()->route('wakasek-kurikulum.dashboard');
    } elseif ($user->hasRole('siswa')) {
        return redirect()->route('siswa.dashboard');
    } elseif ($user->hasRole('teacher')) {
        return redirect()->route('guru.dashboard');
    }
    
    return redirect('/login');
})->name('dashboard');