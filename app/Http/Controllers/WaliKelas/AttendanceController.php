<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $class = $teacher->classes()->first();
        
        if (!$class) {
            return view('walikelas.attendance.index', ['has_class' => false]);
        }

        $date = $request->input('date', date('Y-m-d'));
        
        $students = User::role('siswa')
            ->where('class_id', $class->id)
            ->with(['attendances' => function($q) use ($date) {
                $q->whereDate('date', $date);
            }])
            ->orderByRaw('LOWER(name) ASC')
            ->get();

        return view('walikelas.attendance.index', [
            'has_class' => true,
            'class' => $class,
            'students' => $students,
            'date' => $date
        ]);
    }

    public function report(Request $request)
    {
        $teacher = Auth::user();
        $class = $teacher->classes()->first();
        
        if (!$class) {
            return redirect()->route('wali-kelas.dashboard')->with('error', 'Anda belum terdaftar di kelas manapun.');
        }

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $students = User::role('siswa')
            ->where('class_id', $class->id)
            ->with(['class', 'attendances' => function($q) use ($startDate, $endDate) {
                if ($startDate) $q->whereDate('date', '>=', $startDate);
                if ($endDate) $q->whereDate('date', '<=', $endDate);
            }])
            ->orderBy('name', 'asc')
            ->paginate(50);
        
        // Summary for cards
        $attQuery = Attendance::whereHas('student', function($q) use ($class) {
            $q->where('class_id', $class->id);
        });
        if ($startDate) $attQuery->whereDate('date', '>=', $startDate);
        if ($endDate) $attQuery->whereDate('date', '<=', $endDate);
        
        $summary = [
            'total' => (clone $attQuery)->count(),
            'present' => (clone $attQuery)->where('status', 'present')->count(),
            'absent' => (clone $attQuery)->where('status', 'absent')->count(),
            'late' => (clone $attQuery)->where('status', 'late')->count(),
            'excused' => (clone $attQuery)->where('status', 'excused')->count(),
            'sick' => (clone $attQuery)->where('status', 'sick')->count(),
        ];
        
        return view('walikelas.attendance.report', compact('class', 'students', 'summary'));
    }

    public function studentAttendance(Request $request, $id)
    {
        $teacher = Auth::user();
        $class = $teacher->classes()->first();
        
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));
        
        $student = User::where('class_id', $class->id)
            ->with(['attendances' => function($q) use ($month, $year) {
                $q->whereMonth('date', $month)
                  ->whereYear('date', $year)
                  ->latest('date');
            }])
            ->findOrFail($id);
        
        return response()->json($student);
    }
}
