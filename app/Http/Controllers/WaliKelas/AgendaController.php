<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $class = $teacher->classes()->first();
        
        if (!$class) {
            return view('walikelas.agenda.index', ['has_class' => false]);
        }
        
        $agendas = Agenda::where('class_id', $class->id)
            ->with(['teacher', 'subject'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);
            
        return view('walikelas.agenda.index', [
            'has_class' => true,
            'class' => $class,
            'agendas' => $agendas
        ]);
    }

    public function show(Agenda $agenda)
    {
        $agenda->load(['class', 'teacher', 'subject']);
        return view('walikelas.agenda.show', compact('agenda'));
    }
}
