<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AgendaController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $user = Auth::user();
        $academicYearId = $request->query('academic_year_id');
        if (!$academicYearId) {
            $activeYear = AcademicYear::where('is_active', true)->first();
            $academicYearId = $activeYear ? $activeYear->id : null;
        }

        $class = null;
        if ($academicYearId) {
            $class = $user->getClassInAcademicYear($academicYearId);
        }
        $classId = $class ? $class->id : $user->class_id;

        $query = Agenda::where('class_id', $classId)
            ->with(['teacher', 'subject', 'class']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $agendas = $query->orderBy('date', 'desc')->latest()->paginate(10);
        $subjects = Subject::all();

        $todayStr = Carbon::today()->toDateString();       // 'Y-m-d'
        $yesterdayStr = Carbon::yesterday()->toDateString();

        // Pre-format agenda dates to avoid Carbon issues in Blade
        $groupedAgendas = collect([]);
        foreach ($agendas as $agenda) {
            $dateKey = $agenda->date instanceof \Carbon\Carbon
                ? $agenda->date->toDateString()
                : (string) $agenda->date;

            if (!isset($groupedAgendas[$dateKey])) {
                $groupedAgendas[$dateKey] = collect([]);
            }
            $groupedAgendas[$dateKey]->push($agenda);
        }

        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        return view('siswa.agenda.index', compact('agendas', 'subjects', 'groupedAgendas', 'todayStr', 'yesterdayStr', 'academicYears'));
    }

    public function showJson($id)
    {
        $agenda = Agenda::with(['teacher', 'subject', 'class'])->findOrFail($id);

        $attachmentUrl = null;
        if ($agenda->attachments) {
            $path = is_array($agenda->attachments)
                ? ($agenda->attachments[0] ?? null)
                : $agenda->attachments;
            $attachmentUrl = $path ? asset('storage/' . $path) : null;
        }

        return response()->json([
            'title'        => $agenda->title,
            'subject_name' => $agenda->subject->name ?? 'Umum',
            'date'         => Carbon::parse($agenda->date)->translatedFormat('d F Y'),
            'teacher_name' => $agenda->teacher->name,
            'class_name'   => $agenda->class->name,
            'room'         => $agenda->room,
            'description'  => $agenda->description,
            'attachments'  => $attachmentUrl,
        ]);
    }
}
