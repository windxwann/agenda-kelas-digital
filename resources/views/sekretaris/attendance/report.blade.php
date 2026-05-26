{{-- resources/views/sekretaris/attendance/report.blade.php --}}
@extends('layouts.sekretaris')

@section('title', 'Laporan Presensi')
@section('header', 'Laporan Presensi Siswa')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Laporan Presensi</h1>
            <p class="text-gray-500 mt-1 font-medium text-sm">Analisis data kehadiran siswa berdasarkan filter periode dan kelas.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('sekretaris.print.attendance', request()->query()) }}" target="_blank"
               class="inline-flex items-center px-6 py-3 bg-rose-50 text-rose-700 rounded-2xl border border-rose-100 text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('sekretaris.attendance.report') }}" class="flex flex-col lg:flex-row gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 flex-1 gap-4">
                <!-- Filter Tanggal Mulai -->
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()"
                           class="w-full px-6 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-bold">
                </div>

                <!-- Filter Tanggal Akhir -->
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()"
                           class="w-full px-6 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-bold">
                </div>

                <!-- Filter Status -->
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Status Kehadiran</label>
                    <div class="relative">
                        <select name="status" onchange="this.form.submit()" 
                                class="appearance-none block w-full pl-4 pr-10 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-bold">
                            <option value="">Semua Status</option>
                            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Alpha</option>
                            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                            <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Izin</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="p-3.5 text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg shadow-blue-500/20 hover:from-blue-700 hover:to-indigo-700 transition-all h-[50px] flex items-center justify-center min-w-[50px]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
                
                @if(request()->anyFilled(['start_date', 'end_date', 'status']))
                    <a href="{{ route('sekretaris.attendance.report') }}" 
                       class="p-3.5 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-all h-[50px] flex items-center justify-center min-w-[50px]" 
                       title="Reset Filter">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </form>

        <!-- Search Info -->
        @if(request('start_date') || request('end_date') || request('status'))
            <div class="mt-4 flex flex-wrap items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Filter aktif:</span>
                @if(request('start_date')) <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded">Mulai: {{ request('start_date') }}</span> @endif
                @if(request('end_date')) <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded">Sampai: {{ request('end_date') }}</span> @endif
                @if(request('status')) <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded">Status: {{ ucfirst(request('status')) }}</span> @endif
            </div>
        @endif
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Record</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $summary['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 group hover:border-indigo-500 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Hadir</p>
                    <p class="text-3xl font-black text-indigo-600 mt-1">{{ $summary['present'] }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                @php $presentPct = $summary['total'] > 0 ? ($summary['present'] / $summary['total']) * 100 : 0; @endphp
                <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000" style="width: {{ $presentPct }}%"></div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 group hover:border-rose-500 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Alpha</p>
                    <p class="text-3xl font-black text-rose-600 mt-1">{{ $summary['absent'] }}</p>
                </div>
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 group hover:border-blue-500 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Persentase</p>
                    <p class="text-3xl font-black text-blue-600 mt-1">{{ round($presentPct, 1) }}%</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <h3 class="text-lg font-bold text-gray-900">Rincian Data Presensi</h3>
            <span class="px-4 py-1.5 bg-white text-[10px] font-bold text-gray-400 uppercase tracking-widest rounded-xl border border-gray-100">{{ $attendances->total() }} Records Found</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50/30">
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Siswa</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kelas</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($attendances as $attendance)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d M Y') }}</div>
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-[10px] font-black mr-3">
                                    {{ strtoupper(substr($attendance->student->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-gray-900 truncate">{{ $attendance->student->name }}</div>
                                    <div class="text-[10px] font-medium text-gray-400">NIS: {{ $attendance->student->nis }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-[10px] font-bold bg-blue-50 text-blue-700 rounded-lg border border-blue-100 uppercase tracking-wider">
                                {{ $attendance->student->class->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                            @php
                                $statusStyles = [
                                    'present' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'absent' => 'bg-rose-50 text-rose-700 border-rose-100',
                                    'late' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'excused' => 'bg-sky-50 text-sky-700 border-sky-100'
                                ];
                                $statusLabels = ['present' => 'Hadir', 'absent' => 'Alpha', 'late' => 'Terlambat', 'excused' => 'Izin'];
                                $style = $statusStyles[$attendance->status] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                            @endphp
                            <span class="px-3 py-1 text-[10px] font-black {{ $style }} rounded-lg border uppercase tracking-widest">
                                {{ $statusLabels[$attendance->status] ?? $attendance->status }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-xs font-medium text-gray-500 italic truncate max-w-xs">
                            {{ $attendance->note ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Data Tidak Ditemukan</h3>
                                <p class="text-gray-500 mt-1">Sesuaikan filter untuk mencari data presensi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($attendances->hasPages())
        <div class="px-8 py-6 border-t border-gray-50 bg-gray-50/30">
            {{ $attendances->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
