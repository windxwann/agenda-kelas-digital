<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Agenda;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        if (!$query) {
            return redirect()->back();
        }

        // Search Students
        $students = User::role('siswa')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('nis', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->with('class')
            ->take(5)
            ->get();

        // Search Teachers
        $teachers = User::role('teacher')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->take(5)
            ->get();

        // Search Classes
        $classes = Classes::where('name', 'like', "%{$query}%")
            ->take(5)
            ->get();

        // Search Subjects
        $subjects = Subject::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->take(5)
            ->get();

        // Search Agendas
        $agendas = Agenda::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with(['class', 'teacher'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.search_results', compact(
            'query',
            'students',
            'teachers',
            'classes',
            'subjects',
            'agendas'
        ));
    }
}
