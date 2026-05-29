{{-- resources/views/guru/dashboard.blade.php --}}
@extends('layouts.guru')

@section('title', 'Dashboard Guru')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Welcome Banner (Matched with Admin) -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-600 to-violet-700 rounded-3xl p-8 text-white shadow-xl shadow-blue-500/20">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
        
        <div class="relative flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-black tracking-tight">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                <p class="mt-2 text-lg text-blue-100 font-medium max-w-xl">
                    Sistem siap digunakan. Pantau jadwal mengajar Anda dan kelola jurnal kelas secara real-time hari ini.
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards (Matched with Admin) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-blue-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Jurnal</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $totalAgendas }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-500">
                <span class="font-bold text-blue-600">Aktif</span>
                <span class="mx-2">•</span>
                Catatan mengajar Anda
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-emerald-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kelas Diajar</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $totalClasses }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-500">
                <span class="font-bold text-emerald-600">Semester Ini</span>
                <span class="mx-2">•</span>
                Total kelas di jadwal
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-violet-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mata Pelajaran</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $totalSubjects }}</p>
                </div>
                <div class="w-12 h-12 bg-violet-50 rounded-2xl flex items-center justify-center text-violet-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-500">
                <span class="font-bold text-violet-600">Kurikulum</span>
                <span class="mx-2">•</span>
                Materi yang diampu
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-amber-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Siswa Perwalian</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $totalStudents }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-500">
                <span class="font-bold text-amber-600">Wali Kelas</span>
                <span class="mx-2">•</span>
                {{ $homeroomClass->name ?? 'Belum ada kelas' }}
            </div>
        </div>
    </div>
    
    <!-- Middle Section: Schedules & Activity Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Schedule -->
        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Jadwal Hari Ini</h3>
                    <p class="text-xs text-gray-500">Sesi mengajar Anda hari ini</p>
                </div>
                <div class="p-2 bg-gray-50 text-blue-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="space-y-4 overflow-y-auto pr-2 max-h-[360px] custom-scrollbar">
                @forelse($todaySchedules as $schedule)
                <div class="p-4 bg-gray-50/50 rounded-2xl border border-transparent hover:border-blue-100 hover:bg-white transition-all group flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-blue-600 font-bold shadow-sm mr-4 border border-gray-100">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ $schedule->subject->name }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $schedule->class->name }} • {{ $schedule->room ?? 'R. Kelas' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('guru.agenda.create', ['schedule_id' => $schedule->id]) }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-[10px] font-bold shadow-lg shadow-blue-500/20 hover:from-blue-700 hover:to-indigo-700 transition-all">
                        ISI JURNAL
                    </a>
                </div>
                @empty
                <div class="py-12 text-center">
                    <p class="text-gray-400 italic text-sm">Tidak ada jadwal hari ini</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Monthly Activity Chart -->
        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Aktivitas Mengajar</h3>
                    <p class="text-xs text-gray-500">Tren pengisian jurnal bulanan</p>
                </div>
                <div class="flex items-center bg-gray-50 rounded-xl p-1">
                    <div class="px-3 py-1 text-[10px] font-bold text-blue-600 bg-white rounded-lg shadow-sm">Jurnal</div>
                    <div class="px-3 py-1 text-[10px] font-bold text-gray-500">Aktivitas</div>
                </div>
            </div>
            <div class="flex-1 min-h-[300px]">
                <canvas id="agendaChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Lower Section: Class Attendance & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Homeroom Attendance Summary -->
        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Presensi Kelas Perwalian</h3>
                    <p class="text-xs text-gray-500">Statistik kehadiran hari ini</p>
                </div>
                @if($homeroomClass)
                <a href="{{ route('guru.attendance.index', ['class_id' => $homeroomClass->id]) }}" class="text-xs font-bold text-blue-600 hover:underline">Kelola</a>
                @endif
            </div>
            
            @if($todayAttendance && $todayAttendance->count() > 0)
                <div class="space-y-4">
                    {{-- Progress Overview --}}
                    @php
                        $totalPresent = ($todayAttendance->where('status', 'present')->first()->total ?? 0) + ($todayAttendance->where('status', 'late')->first()->total ?? 0);
                        $totalToday = $todayAttendance->sum('total');
                        $mainPercentage = $totalStudents > 0 ? round(($totalPresent / $totalStudents) * 100) : 0;
                    @endphp
                    <div class="bg-gray-50 rounded-2xl p-4 flex items-center justify-between border border-gray-100/50">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tingkat Kehadiran</p>
                            <p class="text-xl font-black text-gray-900 mt-0.5">{{ $mainPercentage }}% <span class="text-xs font-bold text-gray-400 ml-1">Siswa Hadir</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Data</p>
                            <p class="text-xl font-black text-blue-600 mt-0.5">{{ $totalToday }}<span class="text-xs font-bold text-gray-400 ml-1">/ {{ $totalStudents }}</span></p>
                        </div>
                    </div>

                    {{-- Status Grid --}}
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $statusMap = [
                                'present' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'bar' => 'bg-emerald-500', 'label' => 'Hadir'],
                                'sick' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'bar' => 'bg-orange-500', 'label' => 'Sakit'],
                                'excused' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'bar' => 'bg-sky-500', 'label' => 'Izin'],
                                'late' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'bar' => 'bg-amber-500', 'label' => 'Telat'],
                                'absent' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'bar' => 'bg-rose-500', 'label' => 'Alpha']
                            ];
                        @endphp
                        @foreach($statusMap as $statusCode => $style)
                            @php
                                $count = $todayAttendance->where('status', $statusCode)->first()->total ?? 0;
                                $percentage = $totalToday > 0 ? ($count / $totalToday) * 100 : 0;
                            @endphp
                            <div class="{{ $style['bg'] }} p-4 rounded-2xl border border-transparent hover:shadow-md transition-all {{ $loop->last && $loop->iteration % 2 != 0 ? 'col-span-2' : '' }}">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-[10px] font-black {{ $style['text'] }} uppercase tracking-wider">{{ $style['label'] }}</span>
                                    <span class="text-lg font-black text-gray-900">{{ $count }}</span>
                                </div>
                                <div class="w-full bg-white/50 rounded-full h-1.5 overflow-hidden">
                                    <div class="{{ $style['bar'] }} h-full rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-gray-500 text-sm font-medium">Belum ada data presensi hari ini</p>
                </div>
            @endif
        </div>
        
        <!-- Recent Jurnals -->
        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Jurnal Terbaru</h3>
                    <p class="text-xs text-gray-500">Catatan mengajar terakhir Anda</p>
                </div>
                <a href="{{ route('guru.agenda.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            
            <div class="space-y-4 overflow-y-auto max-h-[300px] custom-scrollbar pr-2">
                @forelse($recentAgendas as $agenda)
                <div class="flex items-center p-3 rounded-2xl hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm mr-4 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ $agenda->title }}</p>
                        <p class="text-[10px] text-gray-500 mt-0.5 truncate">
                            <span class="font-bold text-blue-600">{{ $agenda->class->name }}</span> • 
                            {{ $agenda->subject->name }}
                        </p>
                    </div>
                    <div class="text-right ml-3">
                        <p class="text-[10px] font-bold text-gray-400">{{ $agenda->date->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-center py-8 italic text-sm">Belum ada jurnal</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Quick Actions (Matched with Admin) -->
    <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Aksi Cepat</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('guru.agenda.create') }}" class="flex flex-col items-center p-6 bg-blue-50 rounded-2xl hover:bg-blue-600 hover:text-white transition-all group">
                <div class="w-12 h-12 bg-white text-blue-600 rounded-xl flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider">Isi Jurnal</span>
            </a>
            <a href="{{ route('guru.attendance.index') }}" class="flex flex-col items-center p-6 bg-emerald-50 rounded-2xl hover:bg-emerald-600 hover:text-white transition-all group">
                <div class="w-12 h-12 bg-white text-emerald-600 rounded-xl flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider">Presensi Siswa</span>
            </a>
            <a href="{{ route('guru.report.index') }}" class="flex flex-col items-center p-6 bg-violet-50 rounded-2xl hover:bg-violet-600 hover:text-white transition-all group">
                <div class="w-12 h-12 bg-white text-violet-600 rounded-xl flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider">Unduh Laporan</span>
            </a>
            <a href="#" class="flex flex-col items-center p-6 bg-amber-50 rounded-2xl hover:bg-amber-600 hover:text-white transition-all group">
                <div class="w-12 h-12 bg-white text-amber-600 rounded-xl flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider">Profil Saya</span>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const agendaCanvas = document.getElementById('agendaChart');
        if (agendaCanvas && @json($monthlyStats ?? []).length > 0) {
            const agendaData = @json($monthlyStats ?? []);
            new Chart(agendaCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: agendaData.map(item => {
                        const [year, month] = item.month.split('-');
                        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                        return monthNames[parseInt(month) - 1] + ' ' + year;
                    }),
                    datasets: [{
                        label: 'Jumlah Jurnal',
                        data: agendaData.map(item => item.total),
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Jumlah: ${context.raw} jurnal`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { drawBorder: false },
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection