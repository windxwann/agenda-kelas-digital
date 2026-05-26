<?php

namespace App\Http\Controllers\Wakasek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Classes;
use App\Models\Subject;
use App\Models\Agenda;

class CurriculumController extends Controller
{
    public function index()
    {
        $classQuery = Classes::withCount('agendas');
        $subjectQuery = Subject::withCount('agendas');
        
        if (request('q')) {
            $classQuery->where('name', 'like', '%' . request('q') . '%');
            $subjectQuery->where('name', 'like', '%' . request('q') . '%');
        }
        
        $classes = $classQuery->get();
        $subjects = $subjectQuery->get();
        
        return view('wakasek.curriculum.index', compact('classes', 'subjects'));
    }

    public function progress()
    {
        // Detail progress per mapel per kelas
        $query = Agenda::select('class_id', 'subject_id', \DB::raw('count(*) as total'))
            ->with(['class', 'subject']);
            
        if (request('q')) {
            $query->whereHas('class', function($q) {
                $q->where('name', 'like', '%' . request('q') . '%');
            })->orWhereHas('subject', function($q) {
                $q->where('name', 'like', '%' . request('q') . '%');
            });
        }
            
        $progress = $query->groupBy('class_id', 'subject_id')->get();
            
        return view('wakasek.curriculum.progress', compact('progress'));
    }
}
