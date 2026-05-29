{{-- resources/views/walikelas/agenda/index.blade.php --}}
@extends('layouts.walikelas')

@section('title', 'Agenda Kelas')
@section('header', 'Agenda Kelas')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Header Section -->
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Agenda Kelas {{ $class->name ?? '-' }}</h1>
                <p class="mt-1 text-sm text-gray-500 font-medium">
                    Monitoring jurnal mengajar guru secara harian.
                </p>
            </div>
            @if($has_class)
            <div>
                <form method="GET" action="{{ route('wali-kelas.agenda.index') }}" class="flex items-center gap-2">
                    <select name="academic_year_id" onchange="this.form.submit()" 
                            class="block w-48 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-xs font-semibold">
                        <option value="">Tahun Ajaran (Aktif)</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @if(request('academic_year_id'))
                        <a href="{{ route('wali-kelas.agenda.index') }}" class="p-1.5 text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Reset">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </form>
            </div>
            @endif
        </div>
    </div>

    @if($has_class)
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Waktu & Mapel</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Guru Pengajar</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Materi & Penugasan</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @forelse($agendas as $agenda)
                        <tr class="group hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $agenda->subject->name ?? '-' }}</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $agenda->date->translatedFormat('l, d M Y') }}</span>
                                        @if($agenda->room)
                                        <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                        <span class="inline-flex items-center text-[10px] font-bold text-indigo-500 uppercase tracking-wider">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $agenda->room }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-[10px] font-black shadow-sm border border-indigo-100">
                                        {{ strtoupper(substr($agenda->teacher->name, 0, 1)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900">{{ $agenda->teacher->name }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $agenda->teacher->nip ?? 'NIP -' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="max-w-xs">
                                    <p class="text-sm font-bold text-gray-700 truncate">{{ $agenda->title }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5 line-clamp-1 italic">{{ strip_tags($agenda->description) }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('wali-kelas.agenda.show', $agenda->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all border border-transparent hover:border-indigo-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-medium italic">Belum ada agenda terisi untuk kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($agendas->hasPages())
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
            {{ $agendas->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="bg-white border border-gray-100 rounded-3xl p-24 text-center shadow-sm">
        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8 text-gray-200 border-2 border-dashed border-gray-100">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Akses Terbatas</h3>
        <p class="text-gray-500 font-medium max-w-sm mx-auto leading-relaxed">Halaman ini hanya tersedia untuk Wali Kelas yang sudah ditugaskan ke kelas tertentu.</p>
    </div>
    @endif
</div>
@endsection
