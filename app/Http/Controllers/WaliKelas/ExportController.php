<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Classes;
use App\Models\User;
use App\Models\Attendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function exportAttendance()
    {
        $teacher = Auth::user();
        $class = $teacher->classes()->first();
        
        if (!$class) {
            return view('walikelas.export.attendance', ['has_class' => false]);
        }

        $months = [];
        for ($i = 0; $i < 6; $i++) {
            $date = date('Y-m', strtotime("-$i months"));
            $months[$date] = date('F Y', strtotime($date));
        }

        return view('walikelas.export.attendance', [
            'has_class' => true,
            'class' => $class,
            'months' => $months
        ]);
    }

    public function exportAttendancePDF(Request $request)
    {
        $teacher = Auth::user();
        $class = $teacher->classes()->first();
        
        if (!$class) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $students = User::role('siswa')
            ->where('class_id', $class->id)
            ->with(['attendances' => function($q) use ($startDate, $endDate) {
                if ($startDate) $q->whereDate('date', '>=', $startDate);
                if ($endDate) $q->whereDate('date', '<=', $endDate);
            }])
            ->orderBy('name', 'asc')
            ->get();

        $pdf = Pdf::loadView('walikelas.export.attendance_pdf', compact('class', 'students', 'startDate', 'endDate'));
        return $pdf->download('laporan-presensi-' . $class->name . '-' . now()->format('d-m-Y') . '.pdf');
    }
}
