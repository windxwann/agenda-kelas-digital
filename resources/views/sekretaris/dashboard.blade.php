{{-- resources/views/sekretaris/dashboard.blade.php --}}
@extends('layouts.sekretaris')

@section('title', 'Dashboard')
@section('header', 'Dashboard Sekretaris')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-lg shadow-blue-100">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-blue-50 text-lg opacity-90 font-medium">Kelola agenda kelas dan pantau presensi siswa secara real-time melalui panel kendali Anda.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('sekretaris.agenda.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-blue-700 rounded-2xl font-bold text-sm hover:bg-blue-50 transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Agenda Baru
                    </a>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-48 h-48 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/20">
                    <svg class="w-24 h-24 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <!-- Decorative elements -->
        <div class="absolute -top-12 -right-12 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-indigo-400/20 rounded-full blur-3xl"></div>
    </div>
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 group hover:border-blue-500 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Agenda Bulan Ini</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $stats['total_agendas'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 group hover:border-indigo-500 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Rata-rata Presensi</p>
                    <p class="text-3xl font-black text-indigo-600 mt-1">{{ $stats['avg_attendance'] }}%</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000" style="width: {{ $stats['avg_attendance'] }}%"></div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 group hover:border-blue-500 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Kelas</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $stats['total_classes'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 group hover:border-amber-500 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Siswa</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $stats['total_students'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Actions & Today Summary -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Today Attendance Summary -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="w-2 h-6 bg-blue-600 rounded-full mr-3"></span>
                        Presensi Hari Ini
                    </h3>
                    <a href="{{ route('sekretaris.attendance.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">Detail Lengkap</a>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($today_attendance as $attendance)
                    <div class="p-4 bg-gray-50 rounded-2xl border border-transparent hover:border-blue-100 transition-all">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-bold text-gray-800">{{ $attendance['class_name'] }}</span>
                            <span class="text-xs font-bold px-2 py-1 bg-white text-gray-500 rounded-lg shadow-sm border border-gray-100">
                                {{ $attendance['present'] }}/{{ $attendance['total'] }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            @php $percentage = $attendance['total'] > 0 ? ($attendance['present'] / $attendance['total']) * 100 : 0; @endphp
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Monthly Attendance Chart -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="w-2 h-6 bg-purple-600 rounded-full mr-3"></span>
                        Grafik Kehadiran Kelas
                    </h3>
                    <span class="text-xs font-bold text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">6 Bulan Terakhir</span>
                </div>
                <div class="p-8">
                    <div id="attendanceChart" class="h-80"></div>
                </div>
            </div>

            <!-- Recent Agendas -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="w-2 h-6 bg-blue-600 rounded-full mr-3"></span>
                        Agenda Terbaru
                    </h3>
                    <a href="{{ route('sekretaris.agenda.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">Lihat Semua</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recent_agendas as $agenda)
                    <div class="p-6 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="text-sm font-bold text-gray-900 truncate">{{ $agenda->title }}</h4>
                                    <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-600 rounded-md border border-blue-100 uppercase">{{ $agenda->class->name }}</span>
                                </div>
                                <p class="text-xs text-gray-500 font-medium">
                                    {{ $agenda->date->translatedFormat('d M Y') }} • {{ $agenda->teacher->name }}
                                </p>
                            </div>
                            <a href="{{ route('sekretaris.agenda.edit', $agenda) }}" class="text-gray-400 hover:text-blue-600 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <p class="text-gray-400 text-sm font-medium italic">Belum ada agenda tercatat.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Actions & Shortcuts -->
        <div class="space-y-8">
            <!-- Quick Shortcuts -->
            <div class="bg-gray-900 rounded-3xl p-8 text-white shadow-xl">
                <h3 class="text-lg font-bold mb-6">Pintasan Cepat</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('sekretaris.agenda.create') }}" class="flex flex-col items-center p-4 bg-white/10 rounded-2xl border border-white/10 hover:bg-white/20 transition-all text-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mb-2 shadow-lg shadow-blue-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Buat Agenda</span>
                    </a>
                    <a href="{{ route('sekretaris.attendance.index') }}" class="flex flex-col items-center p-4 bg-white/10 rounded-2xl border border-white/10 hover:bg-white/20 transition-all text-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mb-2 shadow-lg shadow-blue-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Input Presensi</span>
                    </a>
                    <a href="{{ route('sekretaris.attendance.report') }}" class="flex flex-col items-center p-4 bg-white/10 rounded-2xl border border-white/10 hover:bg-white/20 transition-all text-center">
                        <div class="w-10 h-10 bg-rose-500 rounded-xl flex items-center justify-center mb-2 shadow-lg shadow-rose-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Laporan</span>
                    </a>
                    <a href="{{ route('sekretaris.agenda.index') }}" class="flex flex-col items-center p-4 bg-white/10 rounded-2xl border border-white/10 hover:bg-white/20 transition-all text-center">
                        <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center mb-2 shadow-lg shadow-amber-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Semua Agenda</span>
                    </a>
                </div>
            </div>

            <!-- Calendar or Helper Info -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl p-8 border border-blue-100 shadow-sm">
                <h3 class="text-lg font-bold text-blue-900 mb-4">Informasi Penting</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <p class="text-sm text-blue-800 font-medium leading-relaxed">Pastikan agenda harian diisi tepat waktu sebelum jam pelajaran berakhir.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <p class="text-sm text-blue-800 font-medium leading-relaxed">Gunakan fitur <b>Export</b> untuk mencetak laporan mingguan agenda kelas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyData = @json($monthly_attendance);
        if(!monthlyData || monthlyData.length === 0) return;
        
        var options = {
            series: [{
                name: 'Hadir',
                data: monthlyData.map(item => item.present)
            }, {
                name: 'Terlambat',
                data: monthlyData.map(item => item.late)
            }, {
                name: 'Alpha',
                data: monthlyData.map(item => item.absent)
            }, {
                name: 'Izin',
                data: monthlyData.map(item => item.excused)
            }, {
                name: 'Sakit',
                data: monthlyData.map(item => item.sick)
            }],
            chart: {
                height: 350,
                type: 'bar',
                stacked: true,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: {
                    columnWidth: '40%',
                    borderRadius: 4
                }
            },
            colors: ['#10B981', '#F59E0B', '#EF4444', '#3B82F6', '#6B7280'], // Hijau, Oranye, Merah, Biru, Abu-abu
            xaxis: {
                categories: monthlyData.map(item => item.month),
                labels: { style: { colors: '#94a3b8', fontSize: '11px' } }
            },
            yaxis: {
                labels: { style: { colors: '#94a3b8', fontSize: '11px' } }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center'
            },
            tooltip: {
                theme: 'light'
            }
        };

        var chart = new ApexCharts(document.querySelector("#attendanceChart"), options);
        chart.render();
    });
</script>
@endpush