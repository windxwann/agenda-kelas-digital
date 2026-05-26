<?php
// app/Http/Controllers/Sekretaris/PrintController.php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Agenda;
use App\Models\Classes;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PrintController extends Controller
{
    public function printAttendance(Request $request)
    {
        $classId = Auth::user()->class_id;
        if (!$classId) {
            abort(403, 'Anda belum terdaftar di kelas manapun.');
        }

        $query = Attendance::whereHas('student', function($q) use ($classId) {
                $q->where('class_id', $classId);
            })->with(['student.class']);
        
        if ($request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }
        
        if ($request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }
        
        $attendances = $query->latest('date')->get();
        $class = Classes::find($classId);
        
        $pdf = Pdf::loadView('admin.reports.attendance-pdf', compact('attendances', 'class'));
        return $pdf->stream('laporan-presensi-' . $class->name . '-' . date('Y-m-d') . '.pdf');
    }

    public function printAgenda(Request $request)
    {
        $classId = Auth::user()->class_id;
        if (!$classId) {
            abort(403, 'Anda belum terdaftar di kelas manapun.');
        }

        $query = Agenda::where('class_id', $classId)->with(['class', 'teacher', 'subject']);
        
        if ($request->date) {
            $query->where('date', $request->date);
        }
        
        $agendas = $query->latest('date')->get();
        $class = Classes::find($classId);

        $pdf = Pdf::loadView('admin.reports.attendance-pdf', [
            'attendances' => $agendas,
            'class' => $class,
            'is_agenda' => true
        ]);
        
        return $pdf->stream('laporan-agenda-' . $class->name . '-' . date('Y-m-d') . '.pdf');
    }
}
