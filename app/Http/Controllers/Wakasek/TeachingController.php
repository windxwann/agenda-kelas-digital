<?php

namespace App\Http\Controllers\Wakasek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Agenda;

class TeachingController extends Controller
{
    public function index()
    {
        $query = User::role('teacher')->withCount('agendas');
        
        if (request('q')) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . request('q') . '%')
                  ->orWhere('nip', 'like', '%' . request('q') . '%');
            });
        }
            
        $teachers = $query->orderBy('agendas_count', 'desc')->paginate(15);
            
        return view('wakasek.teaching.index', compact('teachers'));
    }

    public function show(User $teacher)
    {
        $query = Agenda::where('teacher_id', $teacher->id)
            ->with(['class', 'subject']);
            
        if (request('q')) {
            $query->where(function($q) {
                $q->where('activity', 'like', '%' . request('q') . '%')
                  ->orWhere('notes', 'like', '%' . request('q') . '%')
                  ->orWhereHas('class', function($c) {
                      $c->where('name', 'like', '%' . request('q') . '%');
                  })
                  ->orWhereHas('subject', function($s) {
                      $s->where('name', 'like', '%' . request('q') . '%');
                  });
            });
        }
            
        $agendas = $query->orderBy('date', 'desc')->paginate(15);
            
        return view('wakasek.teaching.show', compact('teacher', 'agendas'));
    }
}
