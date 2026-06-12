{{-- resources/views/walikelas/dashboard.blade.php --}}
@extends('layouts.walikelas')

@section('title', 'Dashboard Wali Kelas')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8 pb-8">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-700 via-blue-600 to-violet-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-500/20">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-48 sm:w-64 h-48 sm:h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-48 sm:w-64 h-48 sm:h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
        
        <div class="relative flex items-center justify-between">
            <div class="flex-1 text-center sm:text-left">
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight leading-tight">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                <p class="mt-2 text-sm sm:text-lg text-blue-100 font-medium max-w-xl mx-auto sm:mx-0">
                    @if($has_class)
                        Wali Kelas untuk <span class="text-white font-bold">{{ $class->name }}</span>. 
                        Pantau perkembangan kelas binaan Anda secara real-time.
                    @else
                        Sistem belum mendeteksi kelas yang Anda ampu. Silakan hubungi Admin.
                    @endif
                </p>
                <div class="mt-6 flex flex-wrap justify-center sm:justify-start gap-2 sm:gap-3">
                    <div class="px-3 py-1.5 sm:px-4 sm:py-2 bg-white/20 backdrop-blur-md rounded-xl text-[9px] sm:text-xs font-black uppercase tracking-wider">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="px-3 py-1.5 sm:px-4 sm:py-2 bg-emerald-500/20 backdrop-blur-md rounded-xl text-[9px] sm:text-xs font-black uppercase tracking-wider text-emerald-300 flex items-center">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full mr-2 animate-pulse"></span>
                        Portal Aktif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($has_class)
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 gap-3 sm:gap-6">
        @php
            $statsData = [
                ['label' => 'Total Siswa', 'value' => $total_students, 'color' => 'text-indigo-600', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['label' => 'Hadir', 'value' => $today_attendance->get('present')?->count() ?? 0, 'color' => 'text-emerald-600', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Sakit', 'value' => $today_attendance->get('sick')?->count() ?? 0, 'color' => 'text-orange-600', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Izin', 'value' => $today_attendance->get('excused')?->count() ?? 0, 'color' => 'text-sky-600', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Alpha', 'value' => $today_attendance->get('absent')?->count() ?? 0, 'color' => 'text-rose-600', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
        @endphp
        @foreach($statsData as $stat)
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-5 sm:p-6 group hover:border-gray-200 transition-all">
            <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ $stat['label'] }}</p>
            <p class="text-2xl sm:text-3xl font-black {{ $stat['color'] }}">{{ $stat['value'] }}</p>
        </div>
        @endforeach
    </div>
    
    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Agendas -->
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                <div>
                    <h3 class="text-sm sm:text-lg font-black text-gray-900">Agenda Kelas Terakhir</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Aktivitas Mengajar</p>
                </div>
                <a href="{{ route('wali-kelas.agenda.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Semua →</a>
            </div>
            
            <div class="divide-y divide-gray-50">
                @forelse($latest_agendas as $agenda)
                <div class="p-5 hover:bg-gray-50/50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-[10px] font-black shadow-sm group-hover:scale-110 transition-transform">
                            {{ strtoupper(substr($agenda->teacher->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-black text-gray-900 truncate group-hover:text-indigo-600 transition-colors">{{ $agenda->subject->name }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest truncate">{{ $agenda->teacher->name }} • {{ $agenda->date->translatedFormat('d M Y') }}</p>
                        </div>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest rounded-lg border border-emerald-100">Terisi</span>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center"><p class="text-[9px] font-black text-gray-300 uppercase tracking-widest italic">Belum ada agenda terisi</p></div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions Sidebar -->
        <div class="space-y-6">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest px-2">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <a href="{{ route('wali-kelas.attendance.index') }}" class="flex flex-col items-center p-5 bg-white rounded-[2rem] border border-gray-100 hover:border-indigo-500 hover:shadow-xl hover:shadow-indigo-500/5 transition-all group">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-widest text-center">Presensi</span>
                </a>
                <a href="{{ route('wali-kelas.export.attendance') }}" class="flex flex-col items-center p-5 bg-white rounded-[2rem] border border-gray-100 hover:border-emerald-500 hover:shadow-xl hover:shadow-emerald-500/5 transition-all group">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-widest text-center">Export</span>
                </a>
            </div>
            
            <div class="bg-indigo-50 p-6 rounded-[2.5rem] border border-indigo-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-8 -mr-8 w-24 h-24 bg-indigo-100 rounded-full"></div>
                <div class="relative z-10">
                    <h4 class="font-black text-indigo-900 text-xs uppercase tracking-widest mb-2">Butuh Bantuan?</h4>
                    <p class="text-[10px] text-indigo-600 font-bold leading-relaxed">Hubungi operator sekolah jika ada kendala data siswa atau kelas.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
