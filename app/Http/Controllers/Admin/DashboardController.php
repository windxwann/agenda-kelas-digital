<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Agenda;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = $this->getDashboardData();
        return view('admin.dashboard', $data);
    }

    /**
     * API for real-time dashboard updates
     */
    public function statsApi()
    {
        return response()->json($this->getDashboardData());
    }

    private function getDashboardData()
    {
        // Statistik utama
        $stats = [
            'total_classes' => Classes::count(),
            'total_students' => User::role('siswa')->count(),
            'total_teachers' => User::role('teacher')->count(),
            'total_subjects' => Subject::count(),
            'total_agendas_today' => Agenda::query()->where('date', date('Y-m-d'))->count(),
            'attendance_rate' => $this->getAttendanceRate(),
        ];

        // Aktivitas terbaru
        $recent_activities = Agenda::with(['class', 'teacher'])
            ->latest()
            ->take(5)
            ->get();

        // Distribusi kelas
        $class_distribution = Classes::withCount('students')
            ->get()
            ->map(function($class) {
                return [
                    'name' => $class->name,
                    'count' => $class->students_count,
                    'capacity' => $class->capacity ?? 36
                ];
            });

        // Presensi hari ini
        $today_attendance = Attendance::query()->where('date', date('Y-m-d'))
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Agenda per bulan
        $monthly_agendas = Agenda::select(
                DB::raw('strftime("%Y-%m", date) as month'),
                DB::raw('count(*) as total')
            )
            ->whereYear('date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'stats' => $stats, 
            'recent_activities' => $recent_activities, 
            'class_distribution' => $class_distribution,
            'today_attendance' => $today_attendance,
            'monthly_agendas' => $monthly_agendas
        ];
    }

    private function getAttendanceRate()
    {
        $total = Attendance::whereMonth('date', date('m'))->count();
        if ($total == 0) return 0;
        
        $present = Attendance::where('status', 'present')
            ->whereMonth('date', date('m'))
            ->count();
            
        return round(($present / $total) * 100, 2);
    }

    public function monitoringClasses()
    {
        $classes = Classes::with(['homeroomTeacher', 'students'])
            ->withCount(['students', 'agendas'])
            ->get()
            ->map(function($class) {
                $class->attendance_rate = $this->getClassAttendanceRate($class->id);
                return $class;
            });

        return view('admin.monitoring.classes', compact('classes'));
    }

    public function monitoringTeachers()
    {
        $teachers = User::role('teacher')
            ->with(['subjects', 'agendas'])
            ->withCount(['agendas'])
            ->get()
            ->map(function($teacher) {
                $teacher->total_agendas = $teacher->agendas()->count();
                $teacher->monthly_agendas = $teacher->agendas()
                    ->whereMonth('date', date('m'))
                    ->count();
                return $teacher;
            });

        return view('admin.monitoring.teachers', compact('teachers'));
    }

    private function getClassAttendanceRate($classId)
    {
        $total = Attendance::whereHas('student', function($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->whereMonth('date', date('m'))
            ->count();
            
        if ($total == 0) return 0;
        
        $present = Attendance::where('status', 'present')
            ->whereHas('student', function($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->whereMonth('date', date('m'))
            ->count();
            
        return round(($present / $total) * 100, 2);
    }
}