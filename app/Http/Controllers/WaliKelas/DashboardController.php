<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Agenda;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $class = $teacher->classes()->first(); // Get the homeroom class
        
        if (!$class) {
            return view('walikelas.dashboard', [
                'has_class' => false,
                'class_name' => null,
                'total_students' => 0,
                'today_attendance' => null
            ]);
        }

        $total_students = $class->students()->count();
        $today_attendance = Attendance::where('class_id', $class->id)
            ->where('date', date('Y-m-d'))
            ->get()
            ->groupBy('status');

        $latest_agendas = Agenda::where('class_id', $class->id)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->limit(5)
            ->get();

        return view('walikelas.dashboard', [
            'has_class' => true,
            'class' => $class,
            'total_students' => $total_students,
            'today_attendance' => $today_attendance,
            'latest_agendas' => $latest_agendas
        ]);
    }
}
