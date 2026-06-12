{{-- resources/views/guru/dashboard.blade.php --}}
@extends('layouts.guru')

@section('title', 'Dashboard Guru')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8 pb-8">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-600 to-violet-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-500/20">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-48 sm:w-64 h-48 sm:h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-48 sm:w-64 h-48 sm:h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
        
        <div class="relative flex items-center justify-between">
            <div class="flex-1 text-center sm:text-left">
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight leading-tight">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                <p class="mt-2 text-sm sm:text-lg text-blue-100 font-medium max-w-xl mx-auto sm:mx-0">
                    Pantau jadwal mengajar dan kelola jurnal kelas secara real-time hari ini.
                </p>
                <div class="mt-6 flex flex-wrap justify-center sm:justify-start gap-2 sm:gap-3">
                    <div class="px-3 py-1.5 sm:px-4 sm:py-2 bg-white/20 backdrop-blur-md rounded-xl text-[9px] sm:text-xs font-black uppercase tracking-wider">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="px-3 py-1.5 sm:px-4 sm:py-2 bg-emerald-500/20 backdrop-blur-md rounded-xl text-[9px] sm:text-xs font-black uppercase tracking-wider text-emerald-300 flex items-center">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full mr-2 animate-pulse"></span>
                        Sistem Aktif
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white/10 backdrop-blur-md rounded-[2rem] flex items-center justify-center border border-white/20 shadow-inner">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 gap-3 sm:gap-6">
        <div class="bg-white rounded-[2rem] shadow-sm p-5 sm:p-6 border border-gray-100 group hover:border-blue-500 transition-all duration-300">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Jurnal</p>
                    <p class="text-2xl sm:text-3xl font-black text-gray-900 mt-1 leading-none">{{ $totalAgendas }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-[2rem] shadow-sm p-5 sm:p-6 border border-gray-100 group hover:border-emerald-500 transition-all duration-300">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest">Kelas Diajar</p>
                    <p class="text-2xl sm:text-3xl font-black text-gray-900 mt-1 leading-none">{{ $totalClasses }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-[2rem] shadow-sm p-5 sm:p-6 border border-gray-100 group hover:border-violet-500 transition-all duration-300">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest">Mata Pelajaran</p>
                    <p class="text-2xl sm:text-3xl font-black text-gray-900 mt-1 leading-none">{{ $totalSubjects }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-violet-50 rounded-2xl flex items-center justify-center text-violet-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-[2rem] shadow-sm p-5 sm:p-6 border border-gray-100 group hover:border-amber-500 transition-all duration-300">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest">Siswa Perwalian</p>
                    <p class="text-2xl sm:text-3xl font-black text-gray-900 mt-1 leading-none">{{ $totalStudents }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Middle Section: Schedules & Activity Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Schedule -->
        <div class="bg-white rounded-[2.5rem] shadow-sm p-6 sm:p-8 border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <div>
                    <h3 class="text-base sm:text-lg font-black text-gray-900">Jadwal Hari Ini</h3>
                    <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest">Sesi Mengajar Anda</p>
                </div>
                <div class="p-2 sm:p-3 bg-blue-50 text-blue-600 rounded-2xl">
                    <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="space-y-4 overflow-y-auto pr-2 max-h-[360px] custom-scrollbar">
                @forelse($todaySchedules as $schedule)
                <div class="p-4 sm:p-5 bg-gray-50/50 rounded-[1.5rem] border border-transparent hover:border-blue-100 hover:bg-white transition-all group flex items-center justify-between">
                    <div class="flex items-center min-w-0 mr-3">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white rounded-2xl flex items-center justify-center text-blue-600 font-black shadow-sm mr-4 border border-gray-100 flex-shrink-0">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-black text-gray-900 text-sm sm:text-base truncate group-hover:text-blue-600 transition-colors">{{ $schedule->subject->name }}</p>
                            <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5 truncate">{{ $schedule->class->name }} • {{ $schedule->room ?? 'R. Kelas' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('guru.agenda.create', ['schedule_id' => $schedule->id]) }}" 
                       class="flex-shrink-0 px-4 py-2.5 bg-gray-900 group-hover:bg-blue-600 text-white rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] transition-all shadow-lg shadow-gray-200 group-hover:shadow-blue-500/20">
                        ISI
                    </a>
                </div>
                @empty
                <div class="py-12 text-center">
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">Tidak Ada Jadwal Hari Ini</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Activity Chart -->
        <div class="bg-white rounded-[2.5rem] shadow-sm p-6 sm:p-8 border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-base sm:text-lg font-black text-gray-900">Aktivitas Mengajar</h3>
                    <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest">Statistik Jurnal Mengajar</p>
                </div>
            </div>
            <div class="flex-1 min-h-[300px]">
                <canvas id="agendaChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Lower Section: Recent Jurnals & Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Jurnals -->
        <div class="bg-white rounded-[2.5rem] shadow-sm p-6 sm:p-8 border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <div>
                    <h3 class="text-base sm:text-lg font-black text-gray-900">Jurnal Terbaru</h3>
                    <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest">Catatan Terakhir Anda</p>
                </div>
                <a href="{{ route('guru.agenda.index') }}" class="px-4 py-1.5 bg-gray-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-full text-[9px] font-black uppercase tracking-[0.2em] transition-all border border-blue-50 shadow-sm">SEMUA</a>
            </div>
            
            <div class="space-y-3 overflow-y-auto max-h-[300px] custom-scrollbar pr-1">
                @forelse($recentAgendas as $agenda)
                <div class="flex items-center p-4 rounded-2xl hover:bg-gray-50/50 transition-all border border-transparent hover:border-gray-100 group">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-sm mr-4 flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-black text-gray-900 truncate group-hover:text-indigo-600 transition-colors">{{ $agenda->title }}</p>
                        <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5 truncate uppercase tracking-widest font-bold">
                            <span class="text-indigo-600">{{ $agenda->class->name }}</span> • {{ $agenda->subject->name }}
                        </p>
                    </div>
                </div>
                @empty
                <p class="text-[10px] font-black text-gray-300 text-center py-10 uppercase tracking-[0.2em]">Belum Ada Jurnal</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-[2.5rem] shadow-sm p-6 sm:p-8 border border-gray-100">
            <h3 class="text-base sm:text-lg font-black text-gray-900 mb-6 sm:mb-8">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <a href="{{ route('guru.agenda.create') }}" class="flex flex-col items-center p-5 sm:p-6 bg-blue-50/50 rounded-[2rem] hover:bg-blue-600 group transition-all duration-300">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white text-blue-600 rounded-2xl flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-blue-600 group-hover:text-white transition-colors">Isi Jurnal</span>
                </a>
                <a href="{{ route('guru.attendance.index') }}" class="flex flex-col items-center p-5 sm:p-6 bg-emerald-50/50 rounded-[2rem] hover:bg-emerald-600 group transition-all duration-300">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white text-emerald-600 rounded-2xl flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-emerald-600 group-hover:text-white transition-colors">Presensi</span>
                </a>
                <a href="{{ route('guru.report.index') }}" class="flex flex-col items-center p-5 sm:p-6 bg-violet-50/50 rounded-[2rem] hover:bg-violet-600 group transition-all duration-300">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white text-violet-600 rounded-2xl flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-violet-600 group-hover:text-white transition-colors">Laporan</span>
                </a>
                <a href="{{ route('guru.profile') }}" class="flex flex-col items-center p-5 sm:p-6 bg-amber-50/50 rounded-[2rem] hover:bg-amber-600 group transition-all duration-300">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white text-amber-600 rounded-2xl flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-amber-600 group-hover:text-white transition-colors">Profil</span>
                </a>
            </div>
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