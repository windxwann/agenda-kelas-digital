<?php
// app/Http/Controllers/Admin/StudentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Exports\StudentsTemplateExport;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::role('siswa')->with('class');
        
        // Filter berdasarkan kelas
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        
        // Filter berdasarkan gender
        if ($request->has('gender') && $request->gender) {
            $query->where('gender', $request->gender);
        }
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }
        
        $students = $query->orderByRaw('LOWER(name) ASC')->paginate(50);
        $classList = Classes::all();
        
        // Statistik untuk dashboard index
        $maleCount = User::role('siswa')->where('gender', 'L')->count();
        $femaleCount = User::role('siswa')->where('gender', 'P')->count();
        
        return view('admin.students.index', compact('students', 'classList', 'maleCount', 'femaleCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = Classes::all();
        return view('admin.students.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:users,nis',
            'gender' => 'required|in:L,P',
            'class_id' => 'required|exists:classes,id',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:6',
            'nisn' => 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $student = User::create([
            'name' => $request->name,
            'email' => $request->nis . '@agenda.local', // Auto-generate internal email
            'nis' => $request->nis,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
            'status' => 'active',
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'nisn' => $request->nisn,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
        ]);
        
        $student->assignRole('siswa');

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.students.index')
            ->with('success', 'Siswa berhasil ditambahkan! Password: ' . $request->password);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $student)
    {
        $student->load(['class', 'attendances']);
        
        // Statistik presensi
        $attendance_stats = [
            'present' => $student->attendances()->where('status', 'present')->count(),
            'absent' => $student->attendances()->where('status', 'absent')->count(),
            'late' => $student->attendances()->where('status', 'late')->count(),
            'excused' => $student->attendances()->where('status', 'excused')->count(),
        ];
        
        return view('admin.students.show', compact('student', 'attendance_stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $student)
    {
        $classes = Classes::all();
        return view('admin.students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $student)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:users,nis,' . $student->id,
            'gender' => 'required|in:L,P',
            'class_id' => 'required|exists:classes,id',
            'status' => 'required|in:active,inactive,graduated',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6',
            'nisn' => 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['password', 'email']);
        $data['email'] = $request->nis . '@agenda.local'; // Keep sync with NIS
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $student->update($data);
        
        // Handle Role Sekretaris
        if ($request->has('is_secretary')) {
            if (!$student->hasRole('sekretaris')) {
                $student->assignRole('sekretaris');
            }
        } else {
            if ($student->hasRole('sekretaris')) {
                $student->removeRole('sekretaris');
            }
        }

        $message = 'Siswa berhasil diperbarui!';
        if ($request->filled('password')) {
            $message .= ' Password baru: ' . $request->password;
        }

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.students.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $student)
    {
        $student->delete();
        
        $prefix = request()->segment(1);
        return redirect()->route($prefix . '.students.index')
            ->with('success', 'Siswa berhasil dihapus!');
    }

    /**
     * Import students from Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'class_id' => 'nullable|exists:classes,id'
        ]);

        try {
            $import = new StudentsImport($request->class_id);
            Excel::import($import, $request->file('file'));
            
            $importedCount = $import->getImportedCount();
            $skippedCount = $import->getSkippedCount();
            
            $message = "Data siswa berhasil diimport! Berhasil menyimpan {$importedCount} siswa.";
            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} siswa dilewati karena NIS sudah terdaftar sebelumnya)";
            }
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Export template for import
     */
    public function exportTemplate()
    {
        return Excel::download(new StudentsTemplateExport, 'template_siswa.xlsx');
    }

    /**
     * Bulk graduation form
     */
    public function bulkGraduation(Request $request)
    {
        $classes = Classes::where('grade_level', 'XII')->get();
        if ($classes->isEmpty()) {
            $classes = Classes::all(); // Fallback if no XII grade found
        }
        
        $selectedClassId = $request->input('class_id');
        $students = [];
        
        if ($selectedClassId) {
            $students = User::role('siswa')
                ->where('class_id', $selectedClassId)
                ->where('status', 'active')
                ->orderByRaw('LOWER(name) ASC')
                ->get();
        }
        
        return view('admin.students.bulk-graduation', compact('classes', 'students', 'selectedClassId'));
    }

    /**
     * Process bulk graduation
     */
    public function processBulkGraduation(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id'
        ]);

        User::whereIn('id', $request->student_ids)
            ->update(['status' => 'graduated']);

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.students.index')
            ->with('success', count($request->student_ids) . ' siswa berhasil dinyatakan lulus!');
    }

    /**
     * Bulk delete selected students
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'student_ids'   => 'required|array|min:1',
            'student_ids.*' => 'exists:users,id',
        ]);

        $students = User::role('siswa')->whereIn('id', $request->student_ids)->get();
        $count = $students->count();

        foreach ($students as $student) {
            $student->removeRole('siswa');
            $student->delete();
        }

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.students.index')
            ->with('success', "{$count} siswa berhasil dihapus!");
    }
}