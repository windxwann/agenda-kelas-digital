<?php
// app/Http/Controllers/Sekretaris/AgendaController.php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\User;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $classId = Auth::user()->class_id;
        
        if (!$classId) {
            return redirect()->route('sekretaris.dashboard')->with('error', 'Anda belum terdaftar di kelas manapun.');
        }

        $query = Agenda::where('class_id', $classId)->with(['class', 'teacher', 'subject']);
        
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('date') && $request->date) {
            $query->where('date', $request->date);
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $agendas = $query->orderBy('date', 'desc')->paginate(15);
        $classes = Classes::where('id', $classId)->get();
        
        return view('sekretaris.agenda.index', compact('agendas', 'classes'));
    }
    
    public function create()
    {
        $classId = Auth::user()->class_id;
        if (!$classId) {
            return redirect()->route('sekretaris.dashboard')->with('error', 'Anda belum terdaftar di kelas manapun.');
        }

        $classes = Classes::where('id', $classId)->get();
        $subjects = Subject::whereHas('schedules', function($q) use ($classId) {
            $q->where('class_id', $classId);
        })->with('teacher')->get();
        
        if ($subjects->isEmpty()) {
            $subjects = Subject::with('teacher')->get();
        }

        $teachers = User::role('teacher')->get();

        // Load rooms from today's schedule as default
        $today = Carbon::today()->format('l');
        $todaySchedules = Schedule::where('class_id', $classId)
            ->where('day', $today)
            ->whereNotNull('room')
            ->get();

        $scheduleRooms = $todaySchedules->pluck('room')->unique()->values();
        $allRooms = Schedule::where('class_id', $classId)->whereNotNull('room')
            ->distinct()->pluck('room');
        
        return view('sekretaris.agenda.create', compact('classes', 'subjects', 'teachers', 'scheduleRooms', 'allRooms'));
    }

    /**
     * AJAX endpoint: returns subjects & rooms for a given date based on the class schedule.
     */
    public function getScheduleInfo(Request $request)
    {
        $classId = Auth::user()->class_id;
        if (!$classId) {
            return response()->json(['error' => 'No class assigned'], 403);
        }

        $date = $request->input('date');
        if (!$date) {
            return response()->json(['error' => 'Date required'], 422);
        }

        $dayName = Carbon::parse($date)->format('l'); // e.g. 'Monday'

        $schedules = Schedule::where('class_id', $classId)
            ->where('day', $dayName)
            ->with(['subject', 'teacher'])
            ->orderBy('start_time')
            ->get();

        $hasSchedule = $schedules->isNotEmpty();

        if ($hasSchedule) {
            $subjects = $schedules->map(function ($s) {
                return [
                    'id'         => $s->subject_id,
                    'name'       => $s->subject->name,
                    'teacher_id' => $s->teacher_id,
                    'teacher'    => $s->teacher->name,
                    'room'       => $s->room,
                ];
            })->unique('id')->values();

            $rooms = $schedules->whereNotNull('room')->pluck('room')->unique()->values();
        } else {
            // Fallback: return all subjects & all rooms for this class
            $subjects = Subject::whereHas('schedules', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            })->with('teacher')->get()->map(function ($s) {
                return [
                    'id'         => $s->id,
                    'name'       => $s->name,
                    'teacher_id' => $s->teacher_id,
                    'teacher'    => $s->teacher->name ?? '',
                    'room'       => null,
                ];
            });

            $rooms = Schedule::where('class_id', $classId)
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
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'subject_id'  => 'nullable|exists:subjects,id',
            'teacher_id'  => 'required|exists:users,id',
            'room'        => 'required|string|max:255',
            'date'        => 'required|date',
            'description' => 'required|string',
            'status'      => 'required|in:published,draft',
            'attachment'  => 'nullable|file|mimes:pdf,doc,docx,jpg,png,xlsx|max:5120'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $data = $request->except(['attachment', 'class_id']);
        $data['class_id'] = Auth::user()->class_id;
        
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('agendas', 'public');
            $data['attachments'] = $path;
        }
        
        Agenda::create($data);
        
        return redirect()->route('sekretaris.agenda.index')
            ->with('success', 'Agenda berhasil dibuat!');
    }
    
    public function edit(Agenda $agenda)
    {
        $classId = Auth::user()->class_id;
        if ($agenda->class_id != $classId) {
            abort(403, 'Unauthorized action.');
        }

        $classes = Classes::where('id', $classId)->get();
        $subjects = Subject::whereHas('schedules', function($q) use ($classId) {
            $q->where('class_id', $classId);
        })->with('teacher')->get();
        
        if ($subjects->isEmpty()) {
            $subjects = Subject::with('teacher')->get();
        }

        $teachers = User::role('teacher')->get();

        // Rooms for the agenda's date
        $dayName = Carbon::parse($agenda->date)->format('l');
        $scheduleRooms = Schedule::where('class_id', $classId)
            ->where('day', $dayName)
            ->whereNotNull('room')
            ->distinct()
            ->pluck('room')
            ->values();

        $allRooms = Schedule::where('class_id', $classId)
            ->whereNotNull('room')
            ->distinct()
            ->pluck('room')
            ->values();
        
        return view('sekretaris.agenda.edit', compact('agenda', 'classes', 'subjects', 'teachers', 'scheduleRooms', 'allRooms'));
    }
    
    public function update(Request $request, Agenda $agenda)
    {
        if ($agenda->class_id != Auth::user()->class_id) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'subject_id'  => 'nullable|exists:subjects,id',
            'teacher_id'  => 'required|exists:users,id',
            'room'        => 'required|string|max:255',
            'date'        => 'required|date',
            'description' => 'required|string',
            'status'      => 'required|in:published,draft',
            'attachment'  => 'nullable|file|mimes:pdf,doc,docx,jpg,png,xlsx|max:5120'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $data = $request->except(['attachment', 'class_id']);
        $data['class_id'] = Auth::user()->class_id;
        
        if ($request->hasFile('attachment')) {
            // Delete old attachment
            if ($agenda->attachments) {
                Storage::disk('public')->delete($agenda->attachments);
            }
            $path = $request->file('attachment')->store('agendas', 'public');
            $data['attachments'] = $path;
        }
        
        $agenda->update($data);
        
        return redirect()->route('sekretaris.agenda.index')
            ->with('success', 'Agenda berhasil diperbarui!');
    }
    
    public function destroy(Agenda $agenda)
    {
        if ($agenda->class_id != Auth::user()->class_id) {
            abort(403);
        }

        if ($agenda->attachments) {
            Storage::disk('public')->delete($agenda->attachments);
        }
        $agenda->delete();
        
        return redirect()->route('sekretaris.agenda.index')
            ->with('success', 'Agenda berhasil dihapus!');
    }
    
    public function preview($id)
    {
        $agenda = Agenda::with(['class', 'teacher', 'subject'])->findOrFail($id);
        
        if ($agenda->class_id != Auth::user()->class_id) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'title'        => $agenda->title,
            'description'  => $agenda->description,
            'date'         => \Carbon\Carbon::parse($agenda->date)->format('d F Y'),
            'class_name'   => $agenda->class->name,
            'subject_name' => $agenda->subject->name ?? null,
            'teacher_name' => $agenda->teacher->name,
            'room'         => $agenda->room,
            'attachments'  => $agenda->attachments ? asset('storage/' . $agenda->attachments) : null,
            'status'       => $agenda->status
        ]);
    }
}