<?php
// app/Http/Controllers/Sekretaris/DashboardController.php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Classes;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $classId = Auth::user()->class_id;
        
        if (!$classId) {
            $stats = [
                'total_agendas' => 0,
                'avg_attendance' => 0,
                'total_classes' => 0,
                'total_students' => 0,
            ];
            $today_attendance = [];
            $recent_agendas = collect();
            return view('sekretaris.dashboard', compact('stats', 'today_attendance', 'recent_agendas'));
        }

        // Stats filtered by class
        $stats = [
            'total_agendas' => Agenda::where('class_id', $classId)->whereMonth('date', date('m'))->count(),
            'avg_attendance' => $this->getAverageAttendance($classId),
            'total_classes' => 1, // Only their class
            'total_students' => User::role('siswa')->where('class_id', $classId)->count(),
        ];
        
        // Today's attendance for THEIR class
        $today_attendance = [];
        $class = Classes::withCount('students')->find($classId);
        
        if ($class) {
            $present = Attendance::where('class_id', $classId)
                ->where('date', date('Y-m-d'))
                ->where('status', 'present')
                ->count();
            
            $today_attendance[] = [
                'class_name' => $class->name,
                'present' => $present,
                'total' => $class->students_count
            ];
        }
        
        // Recent agendas for THEIR class
        $recent_agendas = Agenda::where('class_id', $classId)
            ->with(['class', 'teacher'])
            ->latest()
            ->take(5)
            ->get();
        
        // Monthly Attendance for Chart - Optimized
        $monthly_attendance = collect();
        $startOfRange = \Carbon\Carbon::now()->subMonths(5)->startOfMonth();
        
        $attendanceStats = Attendance::where('class_id', $classId)
            ->where('date', '>=', $startOfRange)
            ->select(
                DB::raw("strftime('%Y-%m', date) as month_key"),
                DB::raw("status"),
                DB::raw("count(*) as total")
            )
            ->groupBy('month_key', 'status')
            ->get();

        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            
            $monthStats = $attendanceStats->where('month_key', $monthKey);
            
            $present = $monthStats->where('status', 'present')->sum('total');
            $late = $monthStats->where('status', 'late')->sum('total');
            $excused = $monthStats->where('status', 'excused')->sum('total');
            $absent = $monthStats->where('status', 'absent')->sum('total');
            $sick = $monthStats->where('status', 'sick')->sum('total');
            $totalCount = $present + $late + $excused + $absent + $sick;
            
            $monthly_attendance->push([
                'month' => $date->translatedFormat('F'),
                'present' => (int)$present,
                'late' => (int)$late,
                'excused' => (int)$excused,
                'absent' => (int)$absent,
                'sick' => (int)$sick,
                'percentage' => $totalCount > 0 ? round((($present + $late) / $totalCount) * 100, 1) : 0,
            ]);
        }
        
        // Class Histories for Sekretaris (who is a student)
        $class_histories = Auth::user()->classHistories()->with(['class.homeroomTeacher', 'academicYear'])->get()->sortByDesc(function($history) { return $history->academicYear->name ?? ''; });

        return view('sekretaris.dashboard', compact('stats', 'today_attendance', 'recent_agendas', 'monthly_attendance', 'class_histories'));
    }
    
    private function getAverageAttendance($classId)
    {
        $class = Classes::withCount('students')->find($classId);
        if (!$class || $class->students_count == 0) return 0;

        $month = date('m');
        $year = date('Y');

        // Get total potential attendance (students * school days in month so far)
        // For simplicity, we'll count unique dates where attendance was recorded for this class this month
        $activeDays = Attendance::where('class_id', $classId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->distinct()
            ->count('date');

        if ($activeDays == 0) return 0;

        $totalPotential = $activeDays * $class->students_count;
        
        $totalPresent = Attendance::where('class_id', $classId)
            ->whereIn('status', ['present', 'late'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->count();
            
        return round(($totalPresent / $totalPotential) * 100, 1);
    }
}