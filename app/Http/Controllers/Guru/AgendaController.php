<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $agendas = Agenda::where('teacher_id', $teacher->id)
            ->with(['class', 'subject'])
            ->latest()
            ->paginate(10);
            
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
            
        return view('guru.agenda.index', compact('agendas', 'academicYears'));
    }

    public function create(Request $request)
    {
        $teacher = Auth::user();
        
        // Get classes and subjects that the teacher teaches based on their schedule
        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->with(['class', 'subject'])
            ->get();
            
        $classes = $schedules->pluck('class')->unique('id')->values();
        $subjects = $schedules->pluck('subject')->unique('id')->values();
        
        $selected_schedule = null;
        if ($request->has('schedule_id')) {
            $selected_schedule = Schedule::where('teacher_id', $teacher->id)
                ->with(['class', 'subject'])
                ->find($request->schedule_id);
        }

        // Default rooms from today's schedule for this teacher
        $today = Carbon::today()->format('l');
        $defaultRooms = Schedule::where('teacher_id', $teacher->id)
            ->where('day', $today)
            ->whereNotNull('room')
            ->distinct()
            ->pluck('room')
            ->values();

        return view('guru.agenda.create', compact('classes', 'subjects', 'selected_schedule', 'defaultRooms'));
    }

    /**
     * AJAX endpoint: returns subjects & rooms for a given class and date.
     */
    public function getScheduleInfo(Request $request)
    {
        $teacher = Auth::user();
        $classId = $request->input('class_id');
        $date    = $request->input('date');

        if (!$classId || !$date) {
            return response()->json(['error' => 'class_id and date required'], 422);
        }

        $dayName = Carbon::parse($date)->format('l');

        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->where('class_id', $classId)
            ->where('day', $dayName)
            ->with(['subject'])
            ->orderBy('start_time')
            ->get();

        $hasSchedule = $schedules->isNotEmpty();

        if ($hasSchedule) {
            $subjects = $schedules->map(function ($s) {
                return [
                    'id'   => $s->subject_id,
                    'name' => $s->subject->name,
                    'room' => $s->room,
                ];
            })->unique('id')->values();

            $rooms = $schedules->whereNotNull('room')->pluck('room')->unique()->values();
        } else {
            // Fallback: all subjects this teacher teaches in that class
            $subjects = Schedule::where('teacher_id', $teacher->id)
                ->where('class_id', $classId)
                ->with('subject')
                ->get()
                ->map(function ($s) {
                    return [
                        'id'   => $s->subject_id,
                        'name' => $s->subject->name,
                        'room' => null,
                    ];
                })->unique('id')->values();

            $rooms = Schedule::where('teacher_id', $teacher->id)
                ->where('class_id', $classId)
                ->whereNotNull('room')
                ->distinct()
                ->pluck('room')
                ->values();
        }

        return response()->json([
            'has_schedule' => $hasSchedule,
            'day'          => $dayName,
            'subjects'     => $subjects,
            'rooms'        => $rooms,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id'    => 'required|exists:classes,id',
            'subject_id'  => 'required|exists:subjects,id',
            'room'        => 'required|string|max:255',
            'date'        => 'required|date',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Agenda::create([
            'teacher_id'  => Auth::id(),
            'class_id'    => $request->class_id,
            'subject_id'  => $request->subject_id,
            'room'        => $request->room,
            'date'        => $request->date,
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => 'published'
        ]);

        return redirect()->route('guru.agenda.index')->with('success', 'Jurnal mengajar berhasil disimpan.');
    }

    public function show(Agenda $agenda)
    {
        if ($agenda->teacher_id !== Auth::id()) {
            abort(403);
        }
        $agenda->load(['class', 'subject']);
        return view('guru.agenda.show', compact('agenda'));
    }
}
