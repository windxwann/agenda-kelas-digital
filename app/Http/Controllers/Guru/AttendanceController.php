<?php
// app/Http/Controllers/Guru/AttendanceController.php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        
        // Get all classes taught by this teacher
        $classes = Classes::whereHas('schedules', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->get();
        
        $selectedClassId = $request->input('class_id');
        $students = collect();
        
        if ($selectedClassId) {
            $students = User::role('siswa')
                ->where('class_id', $selectedClassId)
                ->with(['attendances' => function($q) {
                    $q->where('date', date('Y-m-d'));
                }])
                ->orderByRaw('LOWER(name) ASC')
                ->get();
        }

        $months = [];
        for ($i = 0; $i < 6; $i++) {
            $date = date('Y-m', strtotime("-$i months"));
            $months[$date] = date('F Y', strtotime($date));
        }

        return view('guru.attendance.index', compact('classes', 'students', 'selectedClassId', 'months'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'attendance' => 'required|array',
        ]);

        $classId = $request->class_id;
        $attendances = $request->input('attendance', []);
        
        foreach ($attendances as $studentId => $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => date('Y-m-d'),
                ],
                [
                    'class_id' => $classId,
                    'status' => $data['status'],
                    'note' => $data['note'] ?? null,
                    'check_in_time' => $data['status'] == 'present' ? date('H:i:s') : null
                ]
            );
        }
        
        return redirect()->route('guru.attendance.index', ['class_id' => $classId])
            ->with('success', 'Presensi berhasil disimpan!');
    }
}