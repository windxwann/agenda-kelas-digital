{{-- resources/views/sekretaris/dashboard.blade.php --}}
@extends('layouts.sekretaris')

@section('title', 'Dashboard')
@section('header', 'Dashboard Sekretaris')

@section('content')
<div class="space-y-6 pb-8">

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-600 to-violet-700 rounded-2xl p-6 sm:p-8 text-white shadow-xl shadow-blue-500/20">
        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-48 h-48 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black tracking-tight leading-tight">Halo, {{ Auth::user()->name }}! 👋</h1>
                <p class="mt-1 text-sm text-blue-100 font-medium">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <a href="{{ route('sekretaris.agenda.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-blue-700 rounded-xl font-black text-xs hover:bg-blue-50 transition-all shadow-lg self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Agenda Baru
            </a>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Agenda</p>
            <p class="text-2xl font-black text-gray-900 mt-1">{{ $stats['total_agendas'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Rata Presensi</p>
            <p class="text-2xl font-black text-indigo-600 mt-1">{{ $stats['avg_attendance'] }}%</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kelas</p>
            <p class="text-2xl font-black text-gray-900 mt-1">{{ $stats['total_classes'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Siswa</p>
            <p class="text-2xl font-black text-gray-900 mt-1">{{ $stats['total_students'] }}</p>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Chart + Presensi --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Grafik Kehadiran --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-5 bg-indigo-500 rounded-full"></div>
                        <h3 class="text-sm font-bold text-gray-900">Grafik Kehadiran</h3>
                    </div>
                    <span class="text-[10px] font-bold text-gray-400 bg-gray-50 border border-gray-100 px-2.5 py-1 rounded-lg">6 Bulan Terakhir</span>
                </div>
                <div class="px-4 pt-2 pb-4">
                    <div id="attendanceChart"></div>
                </div>
            </div>

            {{-- Presensi Hari Ini --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-5 bg-blue-500 rounded-full"></div>
                        <h3 class="text-sm font-bold text-gray-900">Presensi Hari Ini</h3>
                    </div>
                    <a href="{{ route('sekretaris.attendance.index') }}"
                       class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest">
                        Lihat Detail →
                    </a>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($today_attendance as $attendance)
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex justify-between items-center mb-2.5">
                            <span class="text-sm font-bold text-gray-800">{{ $attendance['class_name'] }}</span>
                            <span class="text-xs font-bold text-gray-500 bg-white px-2 py-0.5 rounded-lg border border-gray-100">
                                {{ $attendance['present'] }}/{{ $attendance['total'] }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                            @php $percentage = $attendance['total'] > 0 ? ($attendance['present'] / $attendance['total']) * 100 : 0; @endphp
                            <div class="bg-blue-500 h-1.5 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                        </div>
                        <p class="text-[9px] text-gray-400 font-medium mt-1.5">{{ number_format($percentage, 0) }}% hadir</p>
                    </div>
                    @empty
                    <div class="col-span-2 py-10 text-center">
                        <p class="text-sm text-gray-400 italic">Belum ada data presensi hari ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-6">

            {{-- Pintasan Cepat --}}
            <div class="bg-gray-900 rounded-2xl p-6 text-white shadow-xl">
                <h3 class="text-xs font-bold mb-4 uppercase tracking-widest text-gray-400">Pintasan Cepat</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('sekretaris.agenda.create') }}"
                       class="flex flex-col items-center p-4 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition-all text-center group">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-lg shadow-blue-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-[9px] font-bold uppercase tracking-widest">Buat Agenda</span>
                    </a>
                    <a href="{{ route('sekretaris.attendance.index') }}"
                       class="flex flex-col items-center p-4 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition-all text-center group">
                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-lg shadow-emerald-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[9px] font-bold uppercase tracking-widest">Presensi</span>
                    </a>
                    <a href="{{ route('sekretaris.attendance.report') }}"
                       class="flex flex-col items-center p-4 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition-all text-center group">
                        <div class="w-10 h-10 bg-rose-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-lg shadow-rose-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="text-[9px] font-bold uppercase tracking-widest">Laporan</span>
                    </a>
                    <a href="{{ route('sekretaris.agenda.index') }}"
                       class="flex flex-col items-center p-4 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition-all text-center group">
                        <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-lg shadow-amber-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </div>
                        <span class="text-[9px] font-bold uppercase tracking-widest">Semua Agenda</span>
                    </a>
                </div>
            </div>

            {{-- Agenda Terbaru --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-5 bg-amber-500 rounded-full"></div>
                        <h3 class="text-sm font-bold text-gray-900">Agenda Terbaru</h3>
                    </div>
                    <a href="{{ route('sekretaris.agenda.index') }}"
                       class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest">
                        Lihat Semua →
                    </a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recent_agendas as $agenda)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center text-[10px] font-black flex-shrink-0 mt-0.5">
                                {{ strtoupper(substr($agenda->class->name ?? 'K', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $agenda->title }}</h4>
                                <p class="text-[10px] text-gray-400 font-medium mt-0.5">
                                    {{ $agenda->date->translatedFormat('d M Y') }}
                                    <span class="mx-1">•</span>
                                    {{ $agenda->class->name ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-gray-400 italic">Belum ada agenda tercatat.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Info --}}
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
                <h3 class="text-xs font-bold text-blue-900 uppercase tracking-widest mb-3">Informasi</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-2">
                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></div>
                        <p class="text-xs text-blue-800 font-medium leading-relaxed">Isi agenda sebelum jam pelajaran berakhir.</p>
                    </div>
                    <div class="flex items-start gap-2">
                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></div>
                        <p class="text-xs text-blue-800 font-medium leading-relaxed">Gunakan <b>Export</b> untuk cetak laporan mingguan.</p>
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
        if (!monthlyData || monthlyData.length === 0) return;

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
                height: 280,
                type: 'bar',
                stacked: true,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                animations: { enabled: true, speed: 400 }
            },
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    borderRadius: 5,
                    borderRadiusApplication: 'end'
                }
            },
            colors: ['#10B981', '#F59E0B', '#EF4444', '#3B82F6', '#F97316'],
            xaxis: {
                categories: monthlyData.map(item => item.month),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px' }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } },
                xaxis: { lines: { show: false } }
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '11px',
                fontWeight: 600,
                markers: { size: 8, shape: 'square' },
                itemMargin: { horizontal: 8, vertical: 4 }
            },
            tooltip: {
                theme: 'light',
                shared: true,
                intersect: false
            },
            dataLabels: { enabled: false }
        };

        var chart = new ApexCharts(document.querySelector("#attendanceChart"), options);
        chart.render();
    });
</script>
@endpush