<?php
// app/Http/Controllers/Guru/DashboardController.php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Agenda;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $today = now()->format('l');

        // Today's Teaching Schedule
        $todaySchedules = $teacher->teachingSchedules()
            ->where('day', $today)
            ->with(['class', 'subject'])
            ->orderBy('start_time')
            ->get();

        // Homeroom Class Statistics
        $homeroomClass = $teacher->classHomeroom()->withCount('students')->first();
        $todayAttendance = null;
        if ($homeroomClass) {
            $todayAttendance = Attendance::where('class_id', $homeroomClass->id)
                ->where('date', date('Y-m-d'))
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get();
        }

        // Statistics
        $totalAgendas = Agenda::where('teacher_id', $teacher->id)->count();
        $totalClasses = $teacher->teachingSchedules()->distinct('class_id')->count('class_id');
        $totalSubjects = $teacher->teachingSchedules()->distinct('subject_id')->count('subject_id');
        $totalStudents = $homeroomClass ? $homeroomClass->students_count : 0;

        // Recent Activity
        $recentAgendas = Agenda::where('teacher_id', $teacher->id)
            ->with(['class', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        // Monthly Stats for Chart
        $monthlyStats = Agenda::where('teacher_id', $teacher->id)
            ->where('date', '>=', now()->subMonths(6))
            ->select(
                DB::raw('strftime("%Y-%m", date) as month'),
                DB::raw('count(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('guru.dashboard', [
            'teacher' => $teacher,
            'todaySchedules' => $todaySchedules,
            'homeroomClass' => $homeroomClass,
            'todayAttendance' => $todayAttendance,
            'totalAgendas' => $totalAgendas,
            'totalClasses' => $totalClasses,
            'totalSubjects' => $totalSubjects,
            'totalStudents' => $totalStudents,
            'recentAgendas' => $recentAgendas,
            'monthlyStats' => $monthlyStats
        ]);
    }
}