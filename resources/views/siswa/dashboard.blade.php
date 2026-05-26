{{-- resources/views/siswa/dashboard.blade.php --}}
@extends('layouts.siswa')

@section('title', 'Dashboard')
@section('header', 'Dashboard Siswa')

@section('content')
<div class="space-y-6">

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg shadow-blue-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Halo, {{ Auth::user()->name }}! 👋</h1>
                <p class="mt-1 text-blue-100 text-sm">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 bg-white/20 rounded-lg text-xs font-medium backdrop-blur-sm">
                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                        NIS: {{ Auth::user()->nis ?? '-' }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 bg-white/20 rounded-lg text-xs font-medium backdrop-blur-sm">
                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        {{ Auth::user()->class->name ?? 'Belum ada kelas' }}
                    </span>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <span class="text-4xl font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-gray-100 rounded-xl">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $attendance_stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Pertemuan</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-green-100 rounded-xl">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-green-600">{{ $attendance_stats['present'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Hadir</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-blue-100 rounded-xl">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-blue-600">{{ $attendance_stats['excused'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Izin / Sakit</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-purple-100 rounded-xl">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-purple-600">{{ $attendance_stats['percentage'] }}%</p>
            <p class="text-xs text-gray-500 mt-1">Kehadiran</p>
        </div>
    </div>

    {{-- Agenda Terbaru & Jadwal Hari Ini --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Agenda Terbaru --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800">Agenda Terbaru</h3>
                </div>
                <a href="{{ route('siswa.agenda.index') }}" class="text-xs font-medium text-blue-600 hover:underline">Lihat semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recent_agendas as $agenda)
                <div class="p-5 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $agenda->title }}</h4>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ strip_tags($agenda->description) }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($agenda->date)->translatedFormat('d F Y') }}</span>
                                <span class="text-xs text-gray-400">•</span>
                                <span class="text-xs text-gray-400 truncate">{{ $agenda->teacher->name ?? '-' }}</span>
                            </div>
                        </div>
                        @if($agenda->subject)
                        <span class="flex-shrink-0 px-2 py-1 text-xs bg-indigo-100 text-indigo-700 rounded-full font-medium">{{ $agenda->subject->name }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-10 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 italic">Belum ada agenda</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Jadwal Hari Ini --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Jadwal Hari Ini</h3>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                <a href="{{ route('siswa.schedule.index') }}" class="text-xs font-medium text-blue-600 hover:underline">Semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($today_schedules as $schedule)
                <div class="p-5 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 text-center w-14">
                            <p class="text-sm font-bold text-blue-600">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                        </div>
                        <div class="w-px h-8 bg-gray-200 flex-shrink-0"></div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $schedule->subject->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $schedule->teacher->name ?? '-' }}</p>
                        </div>
                        @if($schedule->room)
                        <span class="flex-shrink-0 text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-lg">{{ $schedule->room }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-10 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 italic">Tidak ada jadwal hari ini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Grafik Kehadiran --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center space-x-3 mb-6">
            <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Grafik Kehadiran</h3>
                <p class="text-xs text-gray-500">6 bulan terakhir</p>
            </div>
        </div>
        <div class="h-80">
            <div id="attendanceChart" class="h-full"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyData = @json($monthly_attendance);
        
        var options = {
            series: [{
                name: 'Hadir',
                data: monthlyData.map(item => item.present),
                color: '#10B981' // emerald-500
            }, {
                name: 'Izin / Sakit',
                data: monthlyData.map(item => item.excused),
                color: '#3B82F6' // blue-500
            }, {
                name: 'Alpha',
                data: monthlyData.map(item => item.absent),
                color: '#EF4444' // red-500
            }],
            chart: {
                type: 'bar',
                height: 320,
                stacked: true,
                toolbar: { show: false },
                zoom: { enabled: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 6,
                    columnWidth: '35%',
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 3,
                colors: ['#ffffff'] // Add clean white space between stacked segments
            },
            xaxis: {
                categories: monthlyData.map(item => item.month),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#94a3b8', fontWeight: 600, fontSize: '13px' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontWeight: 600 },
                    formatter: (value) => { return Math.round(value) } // Only whole numbers for days
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } },
                padding: { top: 0, right: 0, bottom: 0, left: 10 }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                markers: { radius: 12 },
                itemMargin: { horizontal: 15, vertical: 5 },
                fontSize: '14px',
                fontWeight: 600,
                labels: { colors: '#475569' }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val) {
                        return val + " Hari"
                    }
                },
                style: {
                    fontSize: '13px',
                    fontFamily: 'Inter, sans-serif'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#attendanceChart"), options);
        chart.render();
    });
</script>
@endpush