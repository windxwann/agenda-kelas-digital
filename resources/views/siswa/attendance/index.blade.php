{{-- resources/views/siswa/attendance/index.blade.php --}}
@extends('layouts.siswa')

@section('title', 'Presensi Saya')
@section('header', 'Presensi Saya')

@section('content')
@php
    $statusColors = [
        'present' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'absent'  => 'bg-rose-50 text-rose-700 border-rose-100',
        'late'    => 'bg-amber-50 text-amber-700 border-amber-100',
        'sick'    => 'bg-orange-50 text-orange-700 border-orange-100',
        'excused' => 'bg-sky-50 text-sky-700 border-sky-100',
    ];
    $statusBadges = [
        'present' => 'bg-emerald-500',
        'absent'  => 'bg-rose-500',
        'late'    => 'bg-amber-500',
        'sick'    => 'bg-orange-500',
        'excused' => 'bg-sky-500',
    ];
    $statusIcons = [
        'present' => '✓',
        'absent'  => '✗',
        'late'    => '⏰',
        'sick'    => '🤒',
        'excused' => '📋',
    ];
    $statusLabels = [
        'present' => 'Hadir',
        'absent'  => 'Alpha',
        'late'    => 'Terlambat',
        'sick'    => 'Sakit',
        'excused' => 'Izin',
    ];
@endphp

<div class="space-y-6 sm:space-y-8 pb-8">
    {{-- Header Section --}}
    <div class="bg-white rounded-[2.5rem] p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-48 h-48 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Presensi Saya</h1>
                <p class="mt-2 text-xs sm:text-sm text-gray-500 font-medium max-w-2xl">
                    Pantau riwayat kehadiran, statistik bulanan, dan status presensi harian Anda.
                </p>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-6">
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-5 group hover:border-gray-200 transition-all">
            <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total</p>
            <p class="text-xl sm:text-2xl font-black text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-5 group hover:border-emerald-200 transition-all">
            <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Hadir</p>
            <p class="text-xl sm:text-2xl font-black text-emerald-600">{{ $stats['present'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-5 group hover:border-amber-200 transition-all">
            <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Terlambat</p>
            <p class="text-xl sm:text-2xl font-black text-amber-600">{{ $stats['late'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-5 group hover:border-sky-200 transition-all">
            <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Izin / Sakit</p>
            <p class="text-xl sm:text-2xl font-black text-sky-600">{{ ($stats['excused'] ?? 0) + ($stats['sick'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-5 group hover:border-rose-200 transition-all">
            <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Alpha</p>
            <p class="text-xl sm:text-2xl font-black text-rose-600">{{ $stats['absent'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-5 group hover:border-purple-200 transition-all">
            <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kehadiran</p>
            <p class="text-xl sm:text-2xl font-black text-purple-600">{{ $stats['percentage'] }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Calendar & Today Status --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Today's Status Banner --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-6 sm:p-8">
                <h3 class="text-sm font-black text-gray-800 mb-6 uppercase tracking-widest">Status Hari Ini</h3>
                @if($today_attendance)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 rounded-[2rem] border {{ $statusColors[$today_attendance->status] }} gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 rounded-2xl bg-white shadow-sm flex items-center justify-center text-3xl">
                                {{ $statusIcons[$today_attendance->status] }}
                            </div>
                            <div>
                                <p class="text-lg font-black">{{ $statusLabels[$today_attendance->status] }}</p>
                                <div class="flex items-center gap-3 mt-0.5">
                                    <span class="text-[10px] font-bold opacity-70 uppercase tracking-widest">
                                        {{ $today_attendance->check_in_time ? 'Pukul ' . \Carbon\Carbon::parse($today_attendance->check_in_time)->format('H:i') : 'Waktu tidak tercatat' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-[9px] font-black uppercase opacity-60">Tanggal</p>
                            <p class="text-xs font-black">{{ $today_attendance->date->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50/50 rounded-[2rem] p-8 text-center border border-dashed border-gray-200">
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Belum Ada Presensi Hari Ini</p>
                    </div>
                @endif
            </div>

            {{-- Calendar View --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Kalender Presensi</h3>
                    <form method="GET" action="{{ route('siswa.attendance.index') }}">
                        <select name="month" onchange="this.form.submit()" 
                                class="bg-gray-50 border-none rounded-xl text-[10px] font-black text-gray-700 focus:ring-4 focus:ring-blue-500/10 transition-all px-4 py-2 uppercase tracking-widest">
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
                        
                        $attendanceMap = [];
                        foreach($attendances as $att) {
                            $attendanceMap[$att->date->format('Y-m-d')] = $att;
                        }
                    @endphp
                    
                    @foreach($daysOfWeek as $day)
                        <div class="text-[9px] font-black text-gray-400 uppercase text-center py-2">{{ $day }}</div>
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
                                <span class="text-[10px] font-bold {{ $isToday ? 'text-blue-600' : 'text-gray-700' }}">{{ $date->format('d') }}</span>
                                
                                @if($att)
                                    <div class="w-1 h-1 mt-1.5 rounded-full {{ $statusBadges[$att->status] }}"></div>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-6 pt-6 border-t border-gray-50">
                    @foreach($statusLabels as $key => $label)
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full {{ $statusBadges[$key] }}"></div>
                            <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: History Table --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col h-full overflow-hidden">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Riwayat</h3>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-black tracking-widest">Terbaru bulan ini</p>
                </div>
                
                <div class="flex-1 overflow-y-auto divide-y divide-gray-50">
                    @forelse($attendances as $attendance)
                        <div class="p-5 hover:bg-gray-50/50 transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-black text-gray-900 uppercase">
                                    {{ $attendance->date->translatedFormat('d M Y') }}
                                </span>
                                <span class="px-2 py-1 text-[8px] font-black rounded-lg uppercase tracking-widest {{ $statusColors[$attendance->status] }}">
                                    {{ $statusLabels[$attendance->status] }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                    {{ $attendance->check_in_time ? 'Pukul ' . \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : 'Tanpa Jam' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center"><p class="text-[10px] font-black text-gray-400 italic uppercase tracking-widest">Belum ada data</p></div>
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
