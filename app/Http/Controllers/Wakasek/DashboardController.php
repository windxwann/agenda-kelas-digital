<?php

namespace App\Http\Controllers\Wakasek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Agenda;
use App\Models\Classes;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('view', User::class);

        $total_teachers = User::role('teacher')->count();
        $total_classes = Classes::count();
        $total_subjects = Subject::count();
        
        $agendas_today = Agenda::where('date', date('Y-m-d'))->count();
        
        $latest_agendas = Agenda::with(['teacher', 'subject', 'class'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->limit(10)
            ->get();

        // Statistik per kelas
        $class_stats = Classes::withCount('agendas')->get();

        return view('wakasek.dashboard', [
            'total_teachers' => $total_teachers,
            'total_classes' => $total_classes,
            'total_subjects' => $total_subjects,
            'agendas_today' => $agendas_today,
            'latest_agendas' => $latest_agendas,
            'class_stats' => $class_stats
        ]);
    }
}
