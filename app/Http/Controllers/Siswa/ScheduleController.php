<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScheduleController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $academicYearId = $request->query('academic_year_id');
        if (!$academicYearId) {
            $activeYear = AcademicYear::where('is_active', true)->first();
            $academicYearId = $activeYear ? $activeYear->id : null;
        }

        $class = null;
        if ($academicYearId) {
            $class = $user->getClassInAcademicYear($academicYearId);
        }
        $classId = $class ? $class->id : $user->class_id;

        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $delta = (int) $request->input('delta', 0);
        
        $currentDate = Carbon::parse($date)->addWeeks($delta);
        $startOfWeek = $currentDate->copy()->startOfWeek();
        $endOfWeek = $currentDate->copy()->endOfWeek();
        
        $weekRange = $startOfWeek->translatedFormat('d M') . ' - ' . $endOfWeek->translatedFormat('d M Y');
        
        $days = [];
        for ($i = 0; $i < 5; $i++) {
            $dayDate = $startOfWeek->copy()->addDays($i);
            $days[$i] = [
                'name' => $dayDate->translatedFormat('l'),
                'day_number' => $dayDate->format('d'),
                'month_name' => $dayDate->translatedFormat('M'),
                'date' => $dayDate->format('d/m'),
                'is_today' => $dayDate->isToday(),
                'date_full' => $dayDate->format('Y-m-d')
            ];
        }

        $schedules = [];
        $dayMapping = [
            0 => 'Monday',
            1 => 'Tuesday',
            2 => 'Wednesday',
            3 => 'Thursday',
            4 => 'Friday'
        ];
        
        foreach ($dayMapping as $index => $englishDay) {
            $schedules[$index] = Schedule::where('class_id', $classId)
                ->where('day', $englishDay)
                ->with(['subject', 'teacher'])
                ->orderBy('start_time')
                ->get();
        }

        // Find today's index to auto-open accordion
        $todayIndex = 0;
        foreach ($days as $i => $day) {
            if ($day['is_today']) {
                $todayIndex = $i;
                break;
            }
        }

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();

        return view('siswa.schedule.index', [
            'days' => $days,
            'schedules' => $schedules,
            'weekRange' => $weekRange,
            'currentDate' => $currentDate->format('Y-m-d'),
            'todayIndex' => $todayIndex,
            'academicYears' => $academicYears,
            'currentAcademicYearId' => $academicYearId,
        ]);
    }

    public function byDate(Request $request)
    {
        $date = Carbon::parse($request->date);
        $dayName = $date->translatedFormat('l');
        
        $schedules = Schedule::where('class_id', $request->class_id)
            ->where('day', $dayName)
            ->with(['subject', 'teacher'])
            ->orderBy('start_time')
            ->get()
            ->map(function($s) {
                return [
                    'subject_name' => $s->subject->name,
                    'teacher_name' => $s->teacher->name,
                    'start_time' => Carbon::parse($s->start_time)->format('H:i'),
                    'end_time' => Carbon::parse($s->end_time)->format('H:i'),
                    'room' => $s->room
                ];
            });
            
        return response()->json($schedules);
    }

    public function changeWeek(Request $request)
    {
        $currentDate = Carbon::parse($request->current_date)->addWeeks((int) $request->delta);
        $startOfWeek = $currentDate->copy()->startOfWeek();
        $endOfWeek = $currentDate->copy()->endOfWeek();
        
        return response()->json([
            'current_date' => $currentDate->format('Y-m-d'),
            'week_range' => $startOfWeek->translatedFormat('d M') . ' - ' . $endOfWeek->translatedFormat('d M Y')
        ]);
    }

    public function todayDate()
    {
        $date = Carbon::today();
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();
        
        return response()->json([
            'date' => $date->format('Y-m-d'),
            'week_range' => $startOfWeek->translatedFormat('d M') . ' - ' . $endOfWeek->translatedFormat('d M Y')
        ]);
    }
}
