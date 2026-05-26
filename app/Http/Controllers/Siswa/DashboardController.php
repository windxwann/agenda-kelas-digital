<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $classId = $user->class_id;

        // Statistics (matching DB enum: present, absent, late, excused)
        $attendance_stats = [
            'total' => Attendance::where('student_id', $user->id)->count(),
            'present' => Attendance::where('student_id', $user->id)->whereIn('status', ['present', 'late'])->count(),
            'excused' => Attendance::where('student_id', $user->id)->where('status', 'excused')->count(),
        ];
        
        $attendance_stats['percentage'] = $attendance_stats['total'] > 0 
            ? round(($attendance_stats['present'] / $attendance_stats['total']) * 100) 
            : 0;

        // Recent Agendas
        $recent_agendas = Agenda::where('class_id', $classId)
            ->with(['teacher', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        // Today's Schedules
        $dayOfWeek = Carbon::now()->translatedFormat('l');
        $today_schedules = Schedule::where('class_id', $classId)
            ->where('day', $dayOfWeek)
            ->with(['teacher', 'subject'])
            ->orderBy('start_time')
            ->get();

        // Monthly Attendance for Chart
        $monthly_attendance = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthly_attendance->push([
                'month' => $month->translatedFormat('F'),
                'present' => Attendance::where('student_id', $user->id)->whereIn('status', ['present', 'late'])->whereMonth('date', $month->month)->count(),
                'excused' => Attendance::where('student_id', $user->id)->where('status', 'excused')->whereMonth('date', $month->month)->count(),
                'absent' => Attendance::where('student_id', $user->id)->where('status', 'absent')->whereMonth('date', $month->month)->count(),
            ]);
        }

        return view('siswa.dashboard', compact(
            'attendance_stats',
            'recent_agendas',
            'today_schedules',
            'monthly_attendance'
        ));
    }
}
