{{-- resources/views/wakasek/curriculum/index.blade.php --}}
@extends('layouts.wakasek')

@section('title', 'Progres Kurikulum')
@section('header', 'Progres Kurikulum')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Header Section -->
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Monitoring Progres Kurikulum</h1>
                <p class="mt-1 text-sm text-gray-500 font-medium">
                    Ketuntasan materi pembelajaran berdasarkan agenda per kelas dan mata pelajaran.
                </p>
            </div>
            <div class="w-full md:w-auto">
                <a href="{{ route('wakasek-kurikulum.curriculum.progress') }}" class="inline-flex items-center justify-center px-6 py-3.5 bg-blue-600 text-white rounded-2xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:scale-[1.02] transition-all duration-300 group">
                    <span>Detail Progres</span>
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Progress Per Kelas -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Ketuntasan Per Kelas</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Berdasarkan Sesi Terisi</p>
                </div>
                <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1 rounded-lg">Real-time</span>
            </div>
            <div class="p-8 space-y-6 flex-1">
                @foreach($classes as $class)
                <div class="space-y-2 group">
                    <div class="flex justify-between items-center gap-2">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 shrink-0 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center text-[10px] font-black group-hover:scale-110 transition-transform">
                                {{ $loop->iteration }}
                            </div>
                            <span class="text-sm font-bold text-gray-700 group-hover:text-indigo-600 transition-colors truncate">{{ $class->name }}</span>
                        </div>
                        <span class="text-xs font-black text-indigo-600 whitespace-nowrap shrink-0">{{ $class->agendas_count }} Sesi</span>
                    </div>
                    <div class="w-full h-2 bg-gray-50 rounded-full overflow-hidden shadow-inner">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full transition-all duration-700" style="width: {{ min(100, $class->agendas_count * 2) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Progress Per Mata Pelajaran -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Ketuntasan Per Mapel</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Ranking Agenda Terbanyak</p>
                </div>
                <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-lg">Statistik</span>
            </div>
            <div class="p-8 space-y-6 flex-1">
                @foreach($subjects->sortByDesc('agendas_count')->take(10) as $subject)
                <div class="space-y-2 group">
                    <div class="flex justify-between items-center gap-2">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 shrink-0 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-[10px] font-black group-hover:scale-110 transition-transform">
                                {{ strtoupper(substr($subject->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors truncate">{{ $subject->name }}</span>
                        </div>
                        <span class="text-xs font-black text-blue-600 whitespace-nowrap shrink-0">{{ $subject->agendas_count }} Sesi</span>
                    </div>
                    <div class="w-full h-2 bg-gray-50 rounded-full overflow-hidden shadow-inner">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-700" style="width: {{ min(100, $subject->agendas_count * 2) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
