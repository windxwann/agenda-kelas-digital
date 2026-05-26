<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Classes;
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
}
