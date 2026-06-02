{{-- resources/views/walikelas/attendance/index.blade.php --}}
@extends('layouts.walikelas')

@section('title', 'Monitoring Presensi')
@section('header', 'Monitoring Presensi')

@section('content')
<div class="space-y-6 pb-8">
    <!-- Header & Filter -->
    <div class="bg-white rounded-[2rem] p-6 sm:p-8 border border-gray-100 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Presensi Kelas {{ $class->name ?? '-' }}</h1>
                <p class="mt-1 text-xs sm:text-sm text-gray-500 font-medium">
                    Pantau kehadiran harian siswa secara real-time.
                </p>
            </div>
            
            @if($has_class)
            <div class="flex items-center w-full lg:w-auto">
                <form method="GET" action="{{ route('wali-kelas.attendance.index') }}" class="relative group w-full lg:w-48">
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                           class="w-full pl-4 pr-10 py-3 bg-gray-50 border-none rounded-2xl text-xs sm:text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-inner">
                    <div class="absolute right-4 top-3 text-gray-400 group-hover:text-indigo-600 transition-colors pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    @if($has_class)
    <!-- Summary Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        <div class="bg-emerald-50/50 p-4 sm:p-5 rounded-3xl border border-emerald-100 flex flex-col items-center justify-center text-center shadow-sm">
            <span class="text-[9px] sm:text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Hadir</span>
            <span class="text-xl sm:text-2xl font-black text-emerald-700 leading-none">{{ $students->filter(fn($s) => $s->attendances->first()?->status === 'present')->count() }}</span>
        </div>
        <div class="bg-blue-50/50 p-4 sm:p-5 rounded-3xl border border-blue-100 flex flex-col items-center justify-center text-center shadow-sm">
            <span class="text-[9px] sm:text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">Izin/Sakit</span>
            <span class="text-xl sm:text-2xl font-black text-blue-700 leading-none">{{ $students->filter(fn($s) => in_array($s->attendances->first()?->status, ['excused', 'sick']))->count() }}</span>
        </div>
        <div class="bg-amber-50/50 p-4 sm:p-5 rounded-3xl border border-amber-100 flex flex-col items-center justify-center text-center shadow-sm">
            <span class="text-[9px] sm:text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1">Terlambat</span>
            <span class="text-xl sm:text-2xl font-black text-amber-700 leading-none">{{ $students->filter(fn($s) => $s->attendances->first()?->status === 'late')->count() }}</span>
        </div>
        <div class="bg-rose-50/50 p-4 sm:p-5 rounded-3xl border border-rose-100 flex flex-col items-center justify-center text-center shadow-sm">
            <span class="text-[9px] sm:text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1">Alpa</span>
            <span class="text-xl sm:text-2xl font-black text-rose-700 leading-none">{{ $students->filter(fn($s) => $s->attendances->first()?->status === 'absent')->count() }}</span>
        </div>
        <div class="bg-gray-50/50 p-4 sm:p-5 rounded-3xl border border-gray-100 flex flex-col items-center justify-center text-center shadow-sm col-span-2 sm:col-span-1">
            <span class="text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Belum Absen</span>
            <span class="text-xl sm:text-2xl font-black text-gray-600 leading-none">{{ $students->filter(fn($s) => !$s->attendances->first())->count() }}</span>
        </div>
    </div>

    <!-- Attendance Display -->
    <div class="space-y-4">
        {{-- Desktop View --}}
        <div class="hidden sm:block bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-50">
                            <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Siswa</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Waktu</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($students as $student)
                            @php $att = $student->attendances->first(); @endphp
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-[10px] font-black shadow-sm group-hover:scale-110 transition-transform">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $student->name }}</span>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">{{ $student->nis }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    @if($att)
                                        @php
                                            $statusStyles = [
                                                'present' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'excused' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'sick' => 'bg-orange-50 text-orange-600 border-orange-100',
                                                'late' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'absent' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            ];
                                            $statusLabels = [
                                                'present' => 'Hadir',
                                                'excused' => 'Izin',
                                                'sick' => 'Sakit',
                                                'late' => 'Telat',
                                                'absent' => 'Alpa',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1.5 {{ $statusStyles[$att->status] ?? 'bg-gray-50' }} text-[10px] font-black uppercase tracking-widest rounded-lg border">
                                            {{ $statusLabels[$att->status] ?? $att->status }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1.5 bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-gray-100">Belum Absen</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-sm font-bold text-gray-700">
                                        {{ $att && $att->check_in_time ? \Carbon\Carbon::parse($att->check_in_time)->format('H:i') : '-' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs text-gray-500 font-medium italic">{{ $att->note ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-medium italic">Tidak ada data siswa dalam kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile View (Cards) --}}
        <div class="sm:hidden space-y-3">
            @forelse($students as $student)
                @php $att = $student->attendances->first(); @endphp
                <div class="bg-white p-5 rounded-[2rem] border border-gray-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-600 text-white rounded-2xl flex items-center justify-center text-[10px] font-black shadow-lg">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-gray-900 leading-tight">{{ $student->name }}</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">NIS: {{ $student->nis }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-[10px] font-black text-blue-600">{{ $att && $att->check_in_time ? \Carbon\Carbon::parse($att->check_in_time)->format('H:i') : '--:--' }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                        @if($att)
                            @php
                                $statusStyles = [
                                    'present' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'excused' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'sick' => 'bg-orange-50 text-orange-600 border-orange-100',
                                    'late' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'absent' => 'bg-rose-50 text-rose-600 border-rose-100',
                                ];
                                $statusLabels = [
                                    'present' => 'Hadir',
                                    'excused' => 'Izin',
                                    'sick' => 'Sakit',
                                    'late' => 'Telat',
                                    'absent' => 'Alpa',
                                ];
                            @endphp
                            <span class="px-4 py-2 {{ $statusStyles[$att->status] ?? 'bg-gray-50' }} text-[9px] font-black uppercase tracking-[0.2em] rounded-xl border">
                                {{ $statusLabels[$att->status] ?? $att->status }}
                            </span>
                        @else
                            <span class="px-4 py-2 bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-[0.2em] rounded-xl border border-gray-100">Belum Absen</span>
                        @endif

                        @if($att && $att->note)
                            <div class="flex items-center gap-1.5 text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                <span class="text-[10px] font-medium italic truncate max-w-[100px]">{{ $att->note }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white p-10 rounded-[2rem] border border-gray-100 text-center">
                    <p class="text-sm text-gray-400 font-medium italic">Tidak ada data siswa.</p>
                </div>
            @endforelse
        </div>
    </div>
    @else
    <div class="bg-white border border-gray-100 rounded-3xl p-24 text-center shadow-sm">
        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8 text-gray-200 border-2 border-dashed border-gray-200">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Akses Terbatas</h3>
        <p class="text-gray-500 font-medium max-w-sm mx-auto leading-relaxed">Halaman ini hanya tersedia untuk Wali Kelas yang sudah ditugaskan ke kelas tertentu.</p>
    </div>
    @endif
</div>
@endsection
