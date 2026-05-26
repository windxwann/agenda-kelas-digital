{{-- resources/views/siswa/attendance/index.blade.php --}}
@extends('layouts.siswa')

@section('title', 'Presensi Saya')
@section('header', 'Presensi Saya')

@section('content')
@php
    $statusColors = [
        'present' => 'bg-green-50 text-green-700 border-green-100',
        'absent'  => 'bg-red-50 text-red-700 border-red-100',
        'late'    => 'bg-yellow-50 text-yellow-700 border-yellow-100',
        'excused' => 'bg-blue-50 text-blue-700 border-blue-100',
    ];
    $statusBadges = [
        'present' => 'bg-green-500',
        'absent'  => 'bg-red-500',
        'late'    => 'bg-yellow-500',
        'excused' => 'bg-blue-500',
    ];
    $statusIcons = [
        'present' => '✓',
        'absent'  => '✗',
        'late'    => '⏰',
        'excused' => '📋',
    ];
    $statusLabels = [
        'present' => 'Hadir',
        'absent'  => 'Alpha',
        'late'    => 'Terlambat',
        'excused' => 'Izin',
    ];
@endphp

<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Presensi Saya</h1>
            <p class="mt-2 text-base text-gray-500 max-w-2xl leading-relaxed">
                Pantau riwayat kehadiran, statistik bulanan, dan status presensi harian Anda.
            </p>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-green-50 text-green-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-green-600 uppercase">Hadir</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['present'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Total Kehadiran</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-red-50 text-red-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-red-600 uppercase">Alpha</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['absent'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Ketidakhadiran</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-yellow-50 text-yellow-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-yellow-600 uppercase">Telat</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['late'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Terlambat Masuk</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold text-blue-600 uppercase">Izin</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['excused'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Izin atau Sakit</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Calendar & Filters --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Today's Status Banner --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Status Presensi Hari Ini</h3>
                @if($today_attendance)
                    <div class="flex items-center justify-between p-5 rounded-2xl border {{ $statusColors[$today_attendance->status] }}">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl">
                                {{ $statusIcons[$today_attendance->status] }}
                            </div>
                            <div>
                                <p class="text-lg font-bold">{{ $statusLabels[$today_attendance->status] }}</p>
                                <div class="flex items-center gap-3 mt-0.5">
                                    <span class="text-xs font-medium opacity-70 italic">
                                        {{ $today_attendance->check_in_time ? 'Pukul ' . \Carbon\Carbon::parse($today_attendance->check_in_time)->format('H:i') : 'Waktu tidak tercatat' }}
                                    </span>
                                    @if($today_attendance->note)
                                        <span class="text-xs opacity-50">•</span>
                                        <span class="text-xs font-medium opacity-70">{{ $today_attendance->note }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold uppercase opacity-60">Tanggal</p>
                            <p class="text-sm font-bold">{{ $today_attendance->date->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-2xl p-8 text-center border border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-800">Belum Ada Presensi</p>
                        <p class="text-xs text-gray-400 mt-1">Presensi Anda untuk hari ini belum diinput oleh guru.</p>
                    </div>
                @endif
            </div>

            {{-- Calendar View --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Kalender Presensi</h3>
                    <form method="GET" action="{{ route('siswa.attendance.index') }}">
                        <select name="month" onchange="this.form.submit()" 
                                class="bg-gray-50 border-none rounded-xl text-xs font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all px-4 py-2">
                            @foreach($months as $value => $label)
                                <option value="{{ $value }}" {{ request('month', date('Y-m')) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="grid grid-cols-7 gap-2">
                    @php
                        $daysOfWeek = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                        $firstDayOfMonth = \Carbon\Carbon::parse(request('month', date('Y-m')))->startOfMonth();
                        $lastDayOfMonth = \Carbon\Carbon::parse(request('month', date('Y-m')))->endOfMonth();
                        $startDay = $firstDayOfMonth->copy()->startOfWeek();
                        $endDay = $lastDayOfMonth->copy()->endOfWeek();
                        
                        // Map all attendances for the month to an array for easy lookup
                        $attendanceMap = [];
                        foreach($attendances as $att) {
                            $attendanceMap[$att->date->format('Y-m-d')] = $att;
                        }
                    @endphp
                    
                    @foreach($daysOfWeek as $day)
                        <div class="text-[10px] font-black text-gray-400 uppercase text-center py-2">{{ $day }}</div>
                    @endforeach
                    
                    @for($date = $startDay->copy(); $date <= $endDay; $date->addDay())
                        @php
                            $dateStr = $date->format('Y-m-d');
                            $isCurrentMonth = $date->month == \Carbon\Carbon::parse(request('month', date('Y-m')))->month;
                            $att = $attendanceMap[$dateStr] ?? null;
                            $isToday = $date->isToday();
                        @endphp
                        <div class="relative group aspect-square">
                            <div class="w-full h-full rounded-xl flex flex-col items-center justify-center transition-all duration-200 border
                                        {{ $isCurrentMonth ? 'bg-white border-gray-50' : 'bg-gray-50/50 border-transparent opacity-30' }}
                                        {{ $isToday ? 'ring-2 ring-blue-500 border-transparent' : '' }}
                                        {{ $att ? 'hover:shadow-md' : '' }}">
                                <span class="text-xs font-bold {{ $isToday ? 'text-blue-600' : 'text-gray-700' }}">{{ $date->format('d') }}</span>
                                
                                @if($att)
                                    <div class="w-1.5 h-1.5 mt-1.5 rounded-full {{ $statusBadges[$att->status] }}"></div>
                                    
                                    {{-- Tooltip Simple (CSS only or basic title) --}}
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 pointer-events-none">
                                        {{ $statusLabels[$att->status] }} {{ $att->check_in_time ? '(' . \Carbon\Carbon::parse($att->check_in_time)->format('H:i') . ')' : '' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>

                {{-- Legend --}}
                <div class="mt-8 flex flex-wrap items-center justify-center gap-6 pt-6 border-t border-gray-50">
                    @foreach($statusLabels as $key => $label)
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full {{ $statusBadges[$key] }}"></div>
                            <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: History Table --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full overflow-hidden">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Riwayat Terbaru</h3>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">Bulan ini</p>
                </div>
                
                <div class="flex-1 overflow-y-auto divide-y divide-gray-50 scrollbar-hide">
                    @forelse($attendances as $attendance)
                        <div class="p-5 hover:bg-gray-50 transition-all group">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-black text-gray-900 uppercase">
                                    {{ $attendance->date->translatedFormat('d F Y') }}
                                </span>
                                <span class="px-2 py-0.5 text-[9px] font-bold rounded-md uppercase tracking-wider {{ $statusColors[$attendance->status] }}">
                                    {{ $statusLabels[$attendance->status] }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-xs text-gray-500">
                                    {{ $attendance->check_in_time ? 'Pukul ' . \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : 'Tanpa Jam' }}
                                </p>
                                @if($attendance->note)
                                    <span class="text-[10px] text-gray-400 italic truncate max-w-[100px]">{{ $attendance->note }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center">
                            <p class="text-sm text-gray-400 italic">Belum ada data</p>
                        </div>
                    @endforelse
                </div>

                @if($attendances->hasPages())
                    <div class="p-4 bg-gray-50 border-t border-gray-100">
                        {{ $attendances->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection