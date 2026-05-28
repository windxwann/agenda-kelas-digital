<?php
// app/Http/Controllers/Sekretaris/AttendanceController.php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $classId = Auth::user()->class_id;
        if (!$classId) {
            return redirect()->route('sekretaris.dashboard')->with('error', 'Anda belum terdaftar di kelas manapun.');
        }

        $classes = Classes::where('id', $classId)->get();
        $selectedClassId = $classId;
        $date = $request->date ?? date('Y-m-d');
        
        $students = User::role('siswa')
            ->where('class_id', $selectedClassId)
            ->with(['attendances' => function($query) use ($date) {
                $query->where('date', $date);
            }])
            ->get();
        
        return view('sekretaris.attendance.index', compact('classes', 'students', 'selectedClassId', 'date'));
    }
    
    public function store(Request $request)
    {
        $classId = Auth::user()->class_id;
        
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,late,excused,sick',
            'attendance.*.note' => 'nullable|string|max:255',
        ]);
        
        foreach ($request->attendance as $studentId => $data) {
            // Verify student belongs to the secretary's class
            $student = User::where('id', $studentId)->where('class_id', $classId)->first();
            if (!$student) continue;

            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => \Carbon\Carbon::parse($request->date)->format('Y-m-d'),
                ],
                [
                    'class_id' => $classId,
                    'status' => $data['status'],
                    'note' => $data['note'] ?? null,
                    'check_in_time' => $data['status'] == 'present' ? date('H:i:s') : null,
                ]
            );
        }
        
        return redirect()->back()->with('success', 'Presensi berhasil disimpan untuk tanggal ' . $request->date);
    }
    
    public function report(Request $request)
    {
        $classId = Auth::user()->class_id;
        if (!$classId) {
            return redirect()->route('sekretaris.dashboard')->with('error', 'Anda belum terdaftar di kelas manapun.');
        }

        $classes = Classes::where('id', $classId)->get();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = User::role('siswa')->where('class_id', $classId);
        
        $students = $query->with(['class', 'attendances' => function($q) use ($startDate, $endDate) {
            if ($startDate) $q->whereDate('date', '>=', $startDate);
            if ($endDate) $q->whereDate('date', '<=', $endDate);
        }])->orderBy('name', 'asc')->paginate(50);
        
        // Summary for cards
        $attQuery = Attendance::whereHas('student', function($q) use ($classId) {
            $q->where('class_id', $classId);
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
        
        return view('sekretaris.attendance.report', compact('classes', 'students', 'summary'));
    }

    public function studentAttendance(Request $request, $id)
    {
        $classId = Auth::user()->class_id;
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));
        
        $student = User::where('class_id', $classId)
            ->with(['attendances' => function($q) use ($month, $year) {
                $q->whereMonth('date', $month)
                  ->whereYear('date', $year)
                  ->latest('date');
            }])
            ->findOrFail($id);
        
        return response()->json($student);
    }
}
