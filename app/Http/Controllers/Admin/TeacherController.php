<?php
// app/Http/Controllers/Admin/TeacherController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TeachersImport;
use App\Exports\TeachersTemplateExport;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::role('teacher')->with('subjects');
        
        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $teachers = $query->latest()->paginate(10);
        
        return view('admin.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|unique:users,nip',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $teacher = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);
        
        $teacher->assignRole('teacher');

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.teachers.index')
            ->with('success', 'Guru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $teacher)
    {
        $teacher->load(['subjects', 'agendas', 'classHomeroom']);
        
        // Statistik mengajar
        $teaching_stats = [
            'total_subjects' => $teacher->subjects()->count(),
            'total_agendas' => $teacher->agendas()->count(),
            'monthly_agendas' => $teacher->agendas()
                ->whereMonth('date', date('m'))
                ->count(),
        ];
        
        return view('admin.teachers.show', compact('teacher', 'teaching_stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $teacher)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'nip' => 'required|string|unique:users,nip,' . $teacher->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $teacher->update($data);

        $message = 'Guru berhasil diperbarui!';
        if ($request->filled('password')) {
            $message .= ' Password baru: ' . $request->password;
        }

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.teachers.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $teacher)
    {
        // Cek apakah guru masih mengajar
        if ($teacher->subjects()->count() > 0) {
            $prefix = request()->segment(1);
            return redirect()->route($prefix . '.teachers.index')
                ->with('error', 'Guru tidak dapat dihapus karena masih mengajar mata pelajaran!');
        }
        
        if ($teacher->classHomeroom()->count() > 0) {
            $prefix = request()->segment(1);
            return redirect()->route($prefix . '.teachers.index')
                ->with('error', 'Guru tidak dapat dihapus karena menjadi wali kelas!');
        }

        $teacher->delete();

        $prefix = request()->segment(1);
        return redirect()->route($prefix . '.teachers.index')
            ->with('success', 'Guru berhasil dihapus!');
    }

    /**
     * Import teachers from Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new TeachersImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data guru berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Export template for import
     */
    public function exportTemplate()
    {
        return Excel::download(new TeachersTemplateExport, 'template_guru.xlsx');
    }
}