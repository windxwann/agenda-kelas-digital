{{-- resources/views/wakasek/dashboard.blade.php --}}
@extends('layouts.wakasek')

@section('title', 'Dashboard Kurikulum')
@section('header', 'Monitoring Kurikulum')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-600 to-blue-800 rounded-3xl p-8 text-white shadow-xl shadow-blue-500/20">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl"></div>
        
        <div class="relative flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-black tracking-tight">Monitor Kurikulum, {{ explode(' ', Auth::user()->name)[0] }}! 📊</h1>
                <p class="mt-2 text-lg text-blue-100 font-medium max-w-xl">
                    Sistem siap digunakan. Pantau seluruh aktivitas pembelajaran dan progres kurikulum secara real-time hari ini.
                </p>
                <div class="mt-6 flex gap-3">
                    <div class="px-4 py-2 bg-white/20 backdrop-blur-md rounded-xl text-xs font-bold uppercase tracking-wider">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="px-4 py-2 bg-emerald-500/20 backdrop-blur-md rounded-xl text-xs font-bold uppercase tracking-wider text-emerald-300 flex items-center">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></span>
                        Sistem Aktif
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white/10 backdrop-blur-md rounded-3xl flex items-center justify-center border border-white/20 shadow-inner">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-blue-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Guru</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $total_teachers }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-500">
                <span class="font-bold text-blue-600">Aktif</span>
                <span class="mx-2">•</span>
                Pengajar terdaftar
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-indigo-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Kelas</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $total_classes }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-500">
                Monitoring seluruh kelas
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-blue-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mata Pelajaran</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $total_subjects }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-500">
                Kurikulum sekolah
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-emerald-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Agenda Hari Ini</p>
                    <p class="text-3xl font-black text-emerald-600 mt-1">{{ $agendas_today }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-500">
                <span class="font-bold text-emerald-600">Terisi</span>
                <span class="mx-2">•</span>
                Real-time
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Agendas -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between p-6 border-b border-gray-50">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Jurnal Mengajar Terakhir</h3>
                    <p class="text-xs text-gray-500">Log pengisian agenda guru seluruh kelas</p>
                </div>
                <a href="{{ route('wakasek-kurikulum.teaching.index') }}" class="p-2 bg-gray-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Guru & Mapel</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kelas</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($latest_agendas as $agenda)
                        <tr class="group hover:bg-gray-50/50 transition-all">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-[10px] font-bold shadow-sm">
                                        {{ strtoupper(substr($agenda->teacher->name, 0, 1)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $agenda->teacher->name }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase mt-0.5">{{ $agenda->subject->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-gray-50 text-gray-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-gray-100 whitespace-nowrap">
                                    {{ $agenda->class->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500 font-medium italic">
                                {{ $agenda->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-400 italic text-sm">Belum ada aktivitas mengajar hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Class Performance -->
        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Keaktifan Kelas</h3>
                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg">Top 5</span>
            </div>
            
            <div class="space-y-6 flex-1">
                @foreach($class_stats->sortByDesc('agendas_count')->take(5) as $stat)
                <div class="space-y-2 group">
                    <div class="flex justify-between items-center gap-2">
                        <span class="text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors truncate">{{ $stat->name }}</span>
                        <span class="text-xs font-black text-blue-600 whitespace-nowrap">{{ $stat->agendas_count }} Agenda</span>
                    </div>
                    <div class="w-full h-2 bg-gray-50 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-700" style="width: {{ min(100, $stat->agendas_count * 10) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <a href="{{ route('wakasek-kurikulum.curriculum.index') }}" class="mt-6 flex items-center justify-center w-full py-4 text-xs font-black text-blue-600 uppercase tracking-widest hover:bg-blue-50 rounded-2xl transition-all border-2 border-dashed border-blue-100 group">
                <span>Detail Monitoring</span>
                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
