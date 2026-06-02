<?php
// app/Http/Controllers/Admin/SubjectController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subject::with('teacher');
        
        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }
        
        $subjects = $query->latest()->paginate(10);
        
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::role('teacher')->get();
        return view('admin.subjects.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:subjects,code',
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'credit_hours' => 'required|integer|min:1|max:8',
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Subject::create($request->all());

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        $subject->load(['teacher', 'schedules.class']);
        
        // Statistik
        $stats = [
            'total_classes' => $subject->schedules()->distinct('class_id')->count('class_id'),
            'total_schedules' => $subject->schedules()->count(),
            'total_agendas' => $subject->agendas()->count(),
        ];
        
        return view('admin.subjects.show', compact('subject', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $teachers = User::role('teacher')->get();
        return view('admin.subjects.edit', compact('subject', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'credit_hours' => 'required|integer|min:1|max:8',
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $subject->update($request->all());

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.subjects.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        // Cek apakah mapel memiliki jadwal
        if ($subject->schedules()->count() > 0) {
            $prefix = request()->segment(1);
            return redirect()->route($prefix . '.subjects.index')
                ->with('error', 'Mata pelajaran tidak dapat dihapus karena masih memiliki jadwal!');
        }

        $subject->delete();

        $prefix = request()->segment(1);
        return redirect()->route($prefix . '.subjects.index')
            ->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}