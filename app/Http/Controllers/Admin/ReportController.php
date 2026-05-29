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
        $classId = $request->class_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $academicYearId = $request->query('academic_year_id');
        if (!$academicYearId) {
            $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
            $academicYearId = $activeYear ? $activeYear->id : null;
        }

        $query = User::role('siswa');
        
        if ($classId) {
            $query->inClassAndAcademicYear($classId, $academicYearId);
        } else {
            $query->where(function($q) use ($academicYearId) {
                $q->whereHas('classHistories', function($qh) use ($academicYearId) {
                    $qh->where('academic_year_id', $academicYearId);
                })->orWhere(function($qo) use ($academicYearId) {
                    $qo->whereNotNull('class_id')
                       ->whereNotExists(function($qe) use ($academicYearId) {
                           $qe->select(\Illuminate\Support\Facades\DB::raw(1))
                              ->from('class_histories')
                              ->whereColumn('class_histories.user_id', 'users.id')
                              ->where('class_histories.academic_year_id', $academicYearId);
                       });
                });
            });
        }

        // Ambil siswa dengan relasi presensi yang difilter
        $students = $query->with([
            'class', 
            'classHistories' => function($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId)->with('class');
            },
            'attendances' => function($q) use ($startDate, $endDate) {
                if ($startDate) $q->whereDate('date', '>=', $startDate);
                if ($endDate) $q->whereDate('date', '<=', $endDate);
            }
        ])->orderBy('name', 'asc')->paginate(50);

        // Hitung total ringkasan untuk kartu (tetap menggunakan query attendance log)
        $attQuery = Attendance::query();
        if ($startDate) $attQuery->whereDate('date', '>=', $startDate);
        if ($endDate) $attQuery->whereDate('date', '<=', $endDate);
        if ($classId) {
            $attQuery->whereHas('student', function($q) use ($classId, $academicYearId) {
                $q->inClassAndAcademicYear($classId, $academicYearId);
            });
        }
        
        $summary = [
            'total' => (clone $attQuery)->count(),
            'present' => (clone $attQuery)->where('status', 'present')->count(),
            'absent' => (clone $attQuery)->where('status', 'absent')->count(),
            'late' => (clone $attQuery)->where('status', 'late')->count(),
            'excused' => (clone $attQuery)->where('status', 'excused')->count(),
            'sick' => (clone $attQuery)->where('status', 'sick')->count(),
        ];
        
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();
        
        return view('admin.reports.attendance', compact('students', 'classes', 'summary', 'academicYears'));
    }

    /**
     * Get attendance history for a specific student
     */
    public function studentAttendance(Request $request, $id)
    {
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));
        
        $student = User::with(['attendances' => function($q) use ($month, $year) {
            $q->whereMonth('date', $month)
              ->whereYear('date', $year)
              ->latest('date');
        }])->findOrFail($id);
        
        return response()->json($student);
    }
    
    /**
     * Export attendance report to PDF
     */
    public function exportPDF(Request $request)
    {
        $academicYearId = $request->query('academic_year_id');
        if (!$academicYearId) {
            $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
            $academicYearId = $activeYear ? $activeYear->id : null;
        }

        $query = Attendance::with(['student', 'student.class']);
        
        if ($request->has('class_id') && $request->class_id) {
            $query->whereHas('student', function($q) use ($request, $academicYearId) {
                $q->inClassAndAcademicYear($request->class_id, $academicYearId);
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
        $classId = $request->class_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $academicYearId = $request->query('academic_year_id');
        if (!$academicYearId) {
            $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
            $academicYearId = $activeYear ? $activeYear->id : null;
        }

        $studentsQuery = User::role('siswa');
        if ($classId) {
            $studentsQuery->inClassAndAcademicYear($classId, $academicYearId);
        } else {
            $studentsQuery->where(function($q) use ($academicYearId) {
                $q->whereHas('classHistories', function($qh) use ($academicYearId) {
                    $qh->where('academic_year_id', $academicYearId);
                })->orWhere(function($qo) use ($academicYearId) {
                    $qo->whereNotNull('class_id')
                       ->whereNotExists(function($qe) use ($academicYearId) {
                           $qe->select(\Illuminate\Support\Facades\DB::raw(1))
                              ->from('class_histories')
                              ->whereColumn('class_histories.user_id', 'users.id')
                              ->where('class_histories.academic_year_id', $academicYearId);
                       });
                });
            });
        }

        $students = $studentsQuery->with([
            'class',
            'classHistories' => function($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId)->with('class');
            },
            'attendances' => function($q) use ($startDate, $endDate) {
                if ($startDate) $q->whereDate('date', '>=', $startDate);
                if ($endDate) $q->whereDate('date', '<=', $endDate);
            }
        ])
        ->orderBy('name', 'asc')
        ->get();

        $reportData = [];
        foreach ($students as $student) {
            $att = $student->attendances;
            $present = $att->where('status', 'present')->count();
            $absent = $att->where('status', 'absent')->count();
            $late = $att->where('status', 'late')->count();
            $excused = $att->where('status', 'excused')->count();
            $sick = $att->where('status', 'sick')->count();
            $total = $att->count();
            
            $reportData[] = (object)[
                'nis' => $student->nis,
                'name' => $student->name,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'excused' => $excused,
                'sick' => $sick,
                'total' => $total,
                'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
            ];
        }

        $data = [
            'students' => $reportData,
        ];
        
        $filename = 'laporan-presensi-' . ($classId ? Classes::find($classId)->name . '-' : '') . date('Y-m-d') . '.xlsx';
        return Excel::download(new AttendanceReportExport($data), $filename);
    }
}