<?php
// app/Http/Controllers/Guru/ReportController.php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceReportExport;

class ReportController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        
        // Get all classes taught by this teacher
        $schedules = $teacher->teachingSchedules()->with('class')->get();
        $classes = $schedules->pluck('class')->unique('id');

        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $date = date('Y-m', strtotime("-$i months"));
            $months[$date] = [
                'value' => $date,
                'month' => date('m', strtotime($date)),
                'year' => date('Y', strtotime($date)),
                'label' => date('F Y', strtotime($date))
            ];
        }
        
        return view('guru.report.index', compact('classes', 'months'));
    }
    
    public function export(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'month' => 'required',
            'year' => 'required',
            'format' => 'required|in:pdf,excel'
        ]);

        $teacher = Auth::user();
        $class = Classes::findOrFail($request->class_id);
        
        $month = $request->month;
        $year = $request->year;
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $students = User::role('siswa')
            ->where('class_id', $class->id)
            ->with(['attendances' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            }])
            ->get();
        
        $reportData = [];
        foreach ($students as $student) {
            $att = $student->attendances;
            $present = $att->where('status', 'present')->count();
            $absent = $att->where('status', 'absent')->count();
            $late = $att->where('status', 'late')->count();
            $excused = $att->where('status', 'excused')->count();
            $total = $att->count();
            
            $reportData[] = (object)[
                'nis' => $student->nis,
                'name' => $student->name,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'excused' => $excused,
                'total' => $total,
                'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0
            ];
        }
        
        $data = [
            'class' => $class,
            'month' => date('F Y', strtotime($startDate)),
            'students' => $reportData,
            'teacher' => $teacher
        ];
        
        if ($request->input('format') == 'pdf') {
            $pdf = Pdf::loadView('guru.report.attendance-pdf', $data);
            return $pdf->download("laporan-presensi-{$class->name}-{$month}-{$year}.pdf");
        } else {
            return Excel::download(new AttendanceReportExport($data), "laporan-presensi-{$class->name}-{$month}-{$year}.xlsx");
        }
    }
}