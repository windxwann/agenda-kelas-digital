<?php
// app/Http/Controllers/Admin/ScheduleController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['class', 'subject', 'teacher']);
        
        // Filter by class
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        
        // Filter by day
        if ($request->has('day') && $request->day) {
            $query->where('day', $request->day);
        }
        
        $schedules = $query->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('class_id');
            
        $classList = Classes::all();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        return view('admin.schedules.index', compact('schedules', 'classList', 'days'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classList = Classes::all();
        $subjects = Subject::with('teacher')->get();
        $teachers = User::role('teacher')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        return view('admin.schedules.create', compact('classList', 'subjects', 'teachers', 'days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Standardize time formats for precise database comparison (e.g., '09:30' to '09:30:00')
        $startTime = strlen($request->start_time) === 5 ? $request->start_time . ':00' : $request->start_time;
        $endTime = strlen($request->end_time) === 5 ? $request->end_time . ':00' : $request->end_time;

        // 1. Cek bentrok kelas (kelas sudah memiliki pelajaran lain pada jam yang sama)
        $classConflict = Schedule::where('class_id', $request->class_id)
            ->where('day', $request->day)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->exists();
            
        if ($classConflict) {
            return redirect()->back()
                ->with('error', 'Jadwal bentrok dengan jadwal lain di kelas yang sama!')
                ->withInput();
        }

        // 2. Cek bentrok guru (guru sudah memiliki jadwal mengajar di kelas lain pada jam yang sama)
        $teacherConflict = Schedule::where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->exists();

        if ($teacherConflict) {
            return redirect()->back()
                ->with('error', 'Guru yang bersangkutan sudah memiliki jadwal mengajar di jam yang sama!')
                ->withInput();
        }

        // 3. Cek bentrok ruangan (ruangan sudah digunakan untuk jadwal lain pada jam yang sama)
        if ($request->filled('room')) {
            $roomConflict = Schedule::where('room', $request->room)
                ->where('day', $request->day)
                ->where(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                })
                ->exists();

            if ($roomConflict) {
                return redirect()->back()
                    ->with('error', 'Ruangan sudah digunakan untuk jadwal lain pada jam yang sama!')
                    ->withInput();
            }
        }

        Schedule::create($request->all());

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        $schedule->load(['class', 'subject', 'teacher']);
        return view('admin.schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $classList = Classes::all();
        $subjects = Subject::with('teacher')->get();
        $teachers = User::role('teacher')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        return view('admin.schedules.edit', compact('schedule', 'classList', 'subjects', 'teachers', 'days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Standardize time formats for precise database comparison (e.g., '09:30' to '09:30:00')
        $startTime = strlen($request->start_time) === 5 ? $request->start_time . ':00' : $request->start_time;
        $endTime = strlen($request->end_time) === 5 ? $request->end_time . ':00' : $request->end_time;

        // 1. Cek bentrok kelas (kelas sudah memiliki pelajaran lain pada jam yang sama, abaikan jadwal sendiri)
        $classConflict = Schedule::where('class_id', $request->class_id)
            ->where('day', $request->day)
            ->where('id', '!=', $schedule->id)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->exists();
            
        if ($classConflict) {
            return redirect()->back()
                ->with('error', 'Jadwal bentrok dengan jadwal lain di kelas yang sama!')
                ->withInput();
        }

        // 2. Cek bentrok guru (guru sudah memiliki jadwal mengajar di kelas lain pada jam yang sama, abaikan jadwal sendiri)
        $teacherConflict = Schedule::where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where('id', '!=', $schedule->id)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->exists();

        if ($teacherConflict) {
            return redirect()->back()
                ->with('error', 'Guru yang bersangkutan sudah memiliki jadwal mengajar di jam yang sama!')
                ->withInput();
        }

        // 3. Cek bentrok ruangan (ruangan sudah digunakan untuk jadwal lain pada jam yang sama, abaikan jadwal sendiri)
        if ($request->filled('room')) {
            $roomConflict = Schedule::where('room', $request->room)
                ->where('day', $request->day)
                ->where('id', '!=', $schedule->id)
                ->where(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                })
                ->exists();

            if ($roomConflict) {
                return redirect()->back()
                    ->with('error', 'Ruangan sudah digunakan untuk jadwal lain pada jam yang sama!')
                    ->withInput();
            }
        }

        $schedule->update($request->all());

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        $prefix = request()->segment(1);
        return redirect()->route($prefix . '.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }

    /**
     * Get schedule by class (for AJAX)
     */
    public function byClass(Classes $class)
    {
        $schedules = Schedule::with(['subject', 'teacher'])
            ->where('class_id', $class->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');
            
        return response()->json($schedules);
    }
}