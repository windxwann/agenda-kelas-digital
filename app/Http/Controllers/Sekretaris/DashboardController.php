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
            $present = Attendance::whereHas('student', function($q) use ($classId) {
                    $q->where('class_id', $classId);
                })
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
        
        // Monthly Attendance for Chart
        $monthly_attendance = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            $monthly_attendance->push([
                'month' => $month->translatedFormat('F'),
                'present' => Attendance::whereHas('student', function($q) use ($classId) {
                        $q->where('class_id', $classId);
                    })
                    ->whereIn('status', ['present', 'late'])
                    ->whereMonth('date', $month->month)
                    ->count(),
                'excused' => Attendance::whereHas('student', function($q) use ($classId) {
                        $q->where('class_id', $classId);
                    })
                    ->where('status', 'excused')
                    ->whereMonth('date', $month->month)
                    ->count(),
                'absent' => Attendance::whereHas('student', function($q) use ($classId) {
                        $q->where('class_id', $classId);
                    })
                    ->where('status', 'absent')
                    ->whereMonth('date', $month->month)
                    ->count(),
            ]);
        }
        
        return view('sekretaris.dashboard', compact('stats', 'today_attendance', 'recent_agendas', 'monthly_attendance'));
    }
    
    private function getAverageAttendance($classId)
    {
        $total = Attendance::whereHas('student', function($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->whereMonth('date', date('m'))
            ->count();

        if ($total == 0) return 0;
        
        $present = Attendance::whereHas('student', function($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->where('status', 'present')
            ->whereMonth('date', date('m'))
            ->count();
            
        return round(($present / $total) * 100, 1);
    }
}