<?php
// app/Http/Controllers/Admin/ClassController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Classes::with(['homeroomTeacher', 'students'])
            ->withCount('students');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('academic_year', 'like', "%{$search}%");
            });
        }

        $classList = $query->latest()->paginate(10);
            
        return view('admin.classes.index', compact('classList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assignedTeacherIds = Classes::whereNotNull('homeroom_teacher_id')
            ->where('is_active', true)
            ->pluck('homeroom_teacher_id');

        $teachers = User::role('teacher')
            ->whereNotIn('id', $assignedTeacherIds)
            ->get();
            
        $academicYears = \App\Models\AcademicYear::all();
            
        return view('admin.classes.create', compact('teachers', 'academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classes')->where(function ($query) use ($request) {
                    return $query->where('grade_level', $request->grade_level)
                                 ->where('academic_year', $request->academic_year);
                })
            ],
            'major' => 'required|string|max:255',
            'grade_level' => 'required|in:X,XI,XII',
            'academic_year' => 'required|string',
            'homeroom_teacher_id' => [
                'nullable',
                'exists:users,id',
                Rule::unique('classes')->where(function ($query) {
                    return $query->where('is_active', true);
                })
            ],
            'capacity' => 'required|integer|min:1|max:50',
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Classes::create($request->all());

        // Assign wali_kelas role to the teacher
        if ($request->homeroom_teacher_id) {
            $teacher = User::find($request->homeroom_teacher_id);
            if ($teacher && !$teacher->hasRole('wali_kelas')) {
                $teacher->assignRole('wali_kelas');
            }
        }

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.classes.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($class)
    {
        if (!$class instanceof Classes) {
            $class = Classes::findOrFail($class);
        }
        $class->load(['homeroomTeacher', 'students', 'schedules.subject', 'agendas.teacher'])->loadCount('students');
        return view('admin.classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($class)
    {
        if (!$class instanceof Classes) {
            $class = Classes::findOrFail($class);
        }
        $class->loadCount('students');
        
        $assignedTeacherIds = Classes::whereNotNull('homeroom_teacher_id')
            ->where('is_active', true)
            ->where('id', '!=', $class->id)
            ->pluck('homeroom_teacher_id');

        $teachers = User::role('teacher')
            ->whereNotIn('id', $assignedTeacherIds)
            ->get();
            
        $academicYears = \App\Models\AcademicYear::all();
            
        return view('admin.classes.edit', compact('class', 'teachers', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $class)
    {
        if (!$class instanceof Classes) {
            $class = Classes::findOrFail($class);
        }
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classes')->where(function ($query) use ($request) {
                    return $query->where('grade_level', $request->grade_level)
                                 ->where('academic_year', $request->academic_year);
                })->ignore($class->id)
            ],
            'major' => 'required|string|max:255',
            'grade_level' => 'required|in:X,XI,XII',
            'academic_year' => 'required|string',
            'homeroom_teacher_id' => [
                'nullable',
                'exists:users,id',
                Rule::unique('classes')->where(function ($query) {
                    return $query->where('is_active', true);
                })->ignore($class->id)
            ],
            'capacity' => 'required|integer|min:1|max:50',
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldTeacherId = $class->homeroom_teacher_id;
        $class->update($request->all());

        // Handle role changes
        if ($oldTeacherId != $request->homeroom_teacher_id) {
            // Remove role from old teacher if they are no longer a homeroom teacher for any class
            if ($oldTeacherId) {
                $oldTeacher = User::find($oldTeacherId);
                if ($oldTeacher && !Classes::where('homeroom_teacher_id', $oldTeacherId)->exists()) {
                    $oldTeacher->removeRole('wali_kelas');
                }
            }

            // Add role to new teacher
            if ($request->homeroom_teacher_id) {
                $newTeacher = User::find($request->homeroom_teacher_id);
                if ($newTeacher && !$newTeacher->hasRole('wali_kelas')) {
                    $newTeacher->assignRole('wali_kelas');
                }
            }
        }

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.classes.index')
            ->with('success', 'Kelas berhasil diperbarui!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($class)
    {
        if (!$class instanceof Classes) {
            $class = Classes::findOrFail($class);
        }
        // Cek apakah kelas memiliki siswa
        if ($class->students()->count() > 0) {
            $prefix = request()->segment(1);
            return redirect()->route($prefix . '.classes.index')
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa!');
        }

        $teacherId = $class->homeroom_teacher_id;
        $class->delete();

        // Remove role from teacher if they are no longer a homeroom teacher for any class
        if ($teacherId) {
            $teacher = User::find($teacherId);
            if ($teacher && !Classes::where('homeroom_teacher_id', $teacherId)->exists()) {
                $teacher->removeRole('wali_kelas');
            }
        }

        $prefix = request()->segment(1);
        return redirect()->route($prefix . '.classes.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
}