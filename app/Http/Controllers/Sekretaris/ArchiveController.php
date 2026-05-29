<?php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Cari semua class_id yang pernah dimiliki user ini dari class_histories
        $classIds = $user->classHistories()->pluck('class_id')->toArray();
        // Tambahkan class_id saat ini jika belum ada
        if ($user->class_id && !in_array($user->class_id, $classIds)) {
            $classIds[] = $user->class_id;
        }

        $query = Agenda::whereIn('class_id', $classIds)
            ->with(['class', 'teacher', 'subject']);
        
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        // Hanya boleh memfilter kelas yang ADA di riwayat user
        if ($request->has('class_id') && $request->class_id && in_array($request->class_id, $classIds)) {
            $query->where('class_id', $request->class_id);
        }
        
        $agendas = $query->orderBy('date', 'desc')->paginate(15);
        
        // Ambil daftar kelas yang HANYA pernah diikuti oleh user
        $classes = \App\Models\Classes::whereIn('id', $classIds)->get();
        
        return view('sekretaris.agenda.archive', compact('agendas', 'classes'));
    }
}
