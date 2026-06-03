<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\ClassHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassPromotionController extends Controller
{
    /**
     * Show the class promotion form.
     */
    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $classes = Classes::with('students')->orderBy('name')->get();
        $teachers = User::role('teacher')->orderBy('name')->get();

        return view('admin.class_promotions.index', compact('activeYear', 'academicYears', 'classes', 'teachers'));
    }

    /**
     * Preview students in a class for a given academic year.
     */
    public function preview(Request $request)
    {
        $classId = $request->input('class_id');
        $academicYearId = $request->input('academic_year_id');

        $students = User::whereHas('roles', function ($q) {
            $q->where('name', 'siswa');
        })->where('class_id', $classId)->get();

        return response()->json([
            'students' => $students->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'nis' => $s->nis]),
        ]);
    }

    /**
     * Process the class promotion.
     */
    public function promote(Request $request)
    {
        $request->validate([
            'source_class_id'   => 'required|exists:classes,id',
            'target_class_id'   => 'required|exists:classes,id|different:source_class_id',
            'new_homeroom_id'   => 'nullable|exists:users,id',
            'target_year_id'    => 'required|exists:academic_years,id',
            'student_ids'       => 'required|array|min:1',
            'student_ids.*'     => 'exists:users,id',
        ]);

        $sourceClassId  = $request->source_class_id;
        $targetClassId  = $request->target_class_id;
        $newHomeroomId  = $request->new_homeroom_id;
        $targetYearId   = $request->target_year_id;
        $studentIds     = $request->student_ids;

        $sourceClass = Classes::findOrFail($sourceClassId);
        $targetClass = Classes::findOrFail($targetClassId);
        
        if ($sourceClass->major !== $targetClass->major) {
            return redirect()->back()->with('error', 'Siswa tidak dapat dipindahkan ke kelas dengan jurusan yang berbeda!')->withInput();
        }

        // Grade level mapping
        $gradeMapping = [
            'X'   => 10,
            'XI'  => 11,
            'XII' => 12,
        ];

        $sourceLevel = $gradeMapping[$sourceClass->grade_level] ?? 0;
        $targetLevel = $gradeMapping[$targetClass->grade_level] ?? 0;

        if ($sourceLevel == 12) {
            return redirect()->back()->with('error', 'Siswa kelas XII tidak dapat melakukan kenaikan kelas karena sudah tingkat akhir. Silakan gunakan menu Kelulusan untuk meluluskan siswa.')->withInput();
        }

        if ($targetLevel < $sourceLevel) {
            return redirect()->back()->with('error', 'Siswa tidak dapat dinaikkan ke tingkat kelas yang lebih rendah!')->withInput();
        }

        // Find or get the source academic year (the currently active one, which is the "before" year)
        $sourceYear = AcademicYear::where('is_active', true)->first();

        DB::transaction(function () use ($studentIds, $sourceClassId, $targetClassId, $newHomeroomId, $targetYearId, $sourceYear) {
            foreach ($studentIds as $studentId) {
                // 1. Record history for the OLD (source) class if not already done
                if ($sourceYear) {
                    ClassHistory::firstOrCreate([
                        'user_id'          => $studentId,
                        'academic_year_id' => $sourceYear->id,
                    ], [
                        'class_id' => $sourceClassId,
                    ]);
                }

                // 2. Record history for the NEW (target) class in the new academic year
                ClassHistory::firstOrCreate([
                    'user_id'          => $studentId,
                    'academic_year_id' => $targetYearId,
                ], [
                    'class_id' => $targetClassId,
                ]);

                // 3. Update the student's current class_id to the new class
                User::where('id', $studentId)->update(['class_id' => $targetClassId]);
            }
            
            // 4. Optionally update homeroom teacher for the target class
            if ($newHomeroomId) {
                Classes::where('id', $targetClassId)->update(['homeroom_teacher_id' => $newHomeroomId]);
            }
        });

        $targetYear  = AcademicYear::find($targetYearId);
        $targetClass = Classes::find($targetClassId);

        return redirect()->route('admin.class-promotions.index')
            ->with('success', count($studentIds) . ' siswa berhasil dipindahkan ke ' . $targetClass->name . ' (T.A. ' . $targetYear->name . ').');
    }
}
