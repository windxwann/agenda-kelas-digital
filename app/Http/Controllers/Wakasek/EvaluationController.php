<?php

namespace App\Http\Controllers\Wakasek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classes;
use App\Models\Agenda;
use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EvaluationController extends Controller
{
    public function index()
    {
        // 1. General Stats
        $totalTeachers = User::role('teacher')->count();
        $totalStudents = User::role('siswa')->count();
        $totalJournals = Agenda::count();
        
        // 2. Attendance Summary by Class (Percentage)
        $classAttendance = Classes::withCount(['students', 'attendances' => function($q) {
            $q->whereIn('status', ['present', 'late']);
        }])->get()->map(function($class) {
            $totalExpected = $class->students_count > 0 ? ($class->students_count * Attendance::whereHas('student', function($s) use ($class) { $s->where('class_id', $class->id); })->distinct('date')->count()) : 0;
            $presentCount = $class->attendances_count;
            
            return [
                'id' => $class->id,
                'name' => $class->name,
                'percentage' => $totalExpected > 0 ? round(($presentCount / $totalExpected) * 100, 1) : 0,
                'present' => $presentCount
            ];
        })->sortByDesc('percentage');

        // 3. Absence Trends (Current Month)
        $absenceStats = Attendance::where('date', '>=', now()->startOfMonth())
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        // 4. Teaching Compliance (Journal vs Schedule)
        // Simple heuristic: Total agendas vs expected schedules per week
        $expectedPerWeek = Schedule::count();
        $actualThisWeek = Agenda::whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $teachingCompliance = $expectedPerWeek > 0 ? round(($actualThisWeek / $expectedPerWeek) * 100, 1) : 0;

        return view('wakasek.evaluation.index', compact(
            'totalTeachers', 'totalStudents', 'totalJournals', 
            'classAttendance', 'absenceStats', 'teachingCompliance'
        ));
    }

    public function report()
    {
        return view('wakasek.evaluation.report');
    }
}
