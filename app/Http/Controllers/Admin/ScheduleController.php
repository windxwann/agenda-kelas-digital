<?php
// app/Http/Controllers/Admin/ScheduleController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['class', 'subject', 'teacher', 'room']);
        
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        
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

    public function create()
    {
        $classList = Classes::all();
        $subjects = Subject::with('teacher')->get();
        $teachers = User::role('teacher')->get();
        $rooms = Room::where('is_active', true)->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        return view('admin.schedules.create', compact('classList', 'subjects', 'teachers', 'rooms', 'days'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_id' => 'nullable|exists:rooms,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $startTime = strlen($request->start_time) === 5 ? $request->start_time . ':00' : $request->start_time;
        $endTime = strlen($request->end_time) === 5 ? $request->end_time . ':00' : $request->end_time;

        // 1. Cek bentrok kelas
        if (Schedule::where('class_id', $request->class_id)
            ->where('day', $request->day)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)->where('end_time', '>', $startTime);
            })->exists()) {
            return redirect()->back()->with('error', 'Jadwal bentrok dengan jadwal lain di kelas yang sama!')->withInput();
        }

        // 2. Cek bentrok guru
        if (Schedule::where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)->where('end_time', '>', $startTime);
            })->exists()) {
            return redirect()->back()->with('error', 'Guru yang bersangkutan sudah memiliki jadwal mengajar di jam yang sama!')->withInput();
        }

        // 3. Cek bentrok ruangan
        if ($request->filled('room_id')) {
            if (Schedule::where('room_id', $request->room_id)
                ->where('day', $request->day)
                ->where(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)->where('end_time', '>', $startTime);
                })->exists()) {
                return redirect()->back()->with('error', 'Ruangan sudah digunakan untuk jadwal lain pada jam yang sama!')->withInput();
            }
        }

        Schedule::create($request->all());

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.schedules.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(Schedule $schedule)
    {
        $classList = Classes::all();
        $subjects = Subject::with('teacher')->get();
        $teachers = User::role('teacher')->get();
        $rooms = Room::where('is_active', true)->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        return view('admin.schedules.edit', compact('schedule', 'classList', 'subjects', 'teachers', 'rooms', 'days'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_id' => 'nullable|exists:rooms,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $startTime = strlen($request->start_time) === 5 ? $request->start_time . ':00' : $request->start_time;
        $endTime = strlen($request->end_time) === 5 ? $request->end_time . ':00' : $request->end_time;

        // 1. Cek bentrok kelas
        if (Schedule::where('class_id', $request->class_id)
            ->where('id', '!=', $schedule->id)
            ->where('day', $request->day)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)->where('end_time', '>', $startTime);
            })->exists()) {
            return redirect()->back()->with('error', 'Jadwal bentrok dengan jadwal lain di kelas yang sama!')->withInput();
        }

        // 2. Cek bentrok guru
        if (Schedule::where('teacher_id', $request->teacher_id)
            ->where('id', '!=', $schedule->id)
            ->where('day', $request->day)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)->where('end_time', '>', $startTime);
            })->exists()) {
            return redirect()->back()->with('error', 'Guru yang bersangkutan sudah memiliki jadwal mengajar di jam yang sama!')->withInput();
        }

        // 3. Cek bentrok ruangan
        if ($request->filled('room_id')) {
            if (Schedule::where('room_id', $request->room_id)
                ->where('id', '!=', $schedule->id)
                ->where('day', $request->day)
                ->where(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)->where('end_time', '>', $startTime);
                })->exists()) {
                return redirect()->back()->with('error', 'Ruangan sudah digunakan untuk jadwal lain pada jam yang sama!')->withInput();
            }
        }

        $schedule->update($request->all());

        $prefix = $request->segment(1);
        return redirect()->route($prefix . '.schedules.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        $prefix = request()->segment(1);
        return redirect()->route($prefix . '.schedules.index')->with('success', 'Jadwal berhasil dihapus!');
    }

    public function byClass(Classes $class)
    {
        $schedules = Schedule::with(['subject', 'teacher', 'room'])
            ->where('class_id', $class->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');
            
        return response()->json($schedules);
    }
}
