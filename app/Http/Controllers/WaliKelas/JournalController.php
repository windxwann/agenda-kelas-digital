<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $class = $teacher->classes()->first();
        
        if (!$class) {
            return view('walikelas.journal.index', ['has_class' => false]);
        }

        $journals = Agenda::where('class_id', $class->id)
            ->with(['subject', 'teacher'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);
            
        return view('walikelas.journal.index', [
            'has_class' => true,
            'class' => $class,
            'journals' => $journals
        ]);
    }
}
