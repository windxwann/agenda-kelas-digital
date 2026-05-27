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
}
