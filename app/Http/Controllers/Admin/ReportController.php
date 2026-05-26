<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceReportExport;

class ReportController extends Controller
{
    /**
     * Show attendance report page
     */
    public function attendance(Request $request)
    {
        $classes = Classes::all();
        
        $query = Attendance::with(['student', 'student.class']);
        
        // Filter by class
        if ($request->has('class_id') && $request->class_id) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }
        
        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Summary statistics (calculated before paginate() to prevent offset/limit from zeroing out counts)
        $summary = [
            'total' => (clone $query)->count(),
            'present' => (clone $query)->where('status', 'present')->count(),
            'absent' => (clone $query)->where('status', 'absent')->count(),
            'late' => (clone $query)->where('status', 'late')->count(),
            'excused' => (clone $query)->where('status', 'excused')->count(),
        ];
        
        $attendances = $query->latest('date')->paginate(20);
        
        return view('admin.reports.attendance', compact('attendances', 'classes', 'summary'));
    }
    
    /**
     * Export attendance report to PDF
     */
    public function exportPDF(Request $request)
    {
        $query = Attendance::with(['student', 'student.class']);
        
        if ($request->has('class_id') && $request->class_id) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }
        
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        
        $attendances = $query->get();
        $class = $request->class_id ? Classes::find($request->class_id) : null;
        
        $pdf = Pdf::loadView('admin.reports.attendance-pdf', compact('attendances', 'class'));
        return $pdf->download('laporan-presensi-' . date('Y-m-d') . '.pdf');
    }
    
    /**
     * Export attendance report to Excel
     */
    public function exportExcel(Request $request)
    {
        $filters = [
            'class_id' => $request->class_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];
        
        return Excel::download(new AttendanceReportExport($filters), 'laporan-presensi-' . date('Y-m-d') . '.xlsx');
    }
}