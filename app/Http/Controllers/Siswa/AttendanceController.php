<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('siswa.attendance.index');
    }

    public function history()
    {
        return view('siswa.attendance.history');
    }
}
