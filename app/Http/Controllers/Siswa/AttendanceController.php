<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttendanceController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month', date('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $stats = [
            'total' => Attendance::where('student_id', $user->id)->count(),
            'present' => Attendance::where('student_id', $user->id)->whereIn('status', ['present', 'late'])->count(),
            'absent' => Attendance::where('student_id', $user->id)->where('status', 'absent')->count(),
            'late' => Attendance::where('student_id', $user->id)->where('status', 'late')->count(),
            'sick' => Attendance::where('student_id', $user->id)->where('status', 'sick')->count(),
            'excused' => Attendance::where('student_id', $user->id)->where('status', 'excused')->count(),
        ];
        
        $stats['percentage'] = $stats['total'] > 0 
            ? round(($stats['present'] / $stats['total']) * 100) 
            : 0;

        $today_attendance = Attendance::where('student_id', $user->id)
            ->where('date', Carbon::today()->toDateString())
            ->first();

        $attendances = Attendance::where('student_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->paginate(15);

        // For dropdown
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths($i);
            $months[$date->format('Y-m')] = $date->translatedFormat('F Y');
        }

        return view('siswa.attendance.index', compact('stats', 'today_attendance', 'attendances', 'months'));
    }
}
