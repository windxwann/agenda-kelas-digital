{{-- resources/views/admin/reports/attendance.blade.php --}}
@extends('layouts.admin')

@section('title', 'Laporan Presensi')
@section('header', 'Laporan Presensi Siswa')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Laporan Presensi</h1>
            <p class="text-gray-500 mt-1 font-medium">Analisis data kehadiran siswa berdasarkan filter periode dan kelas.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.reports.export.pdf', request()->query()) }}" target="_blank"
               class="inline-flex items-center px-5 py-2.5 bg-rose-50 text-rose-700 rounded-2xl border border-rose-100 text-xs font-bold uppercase tracking-wider hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export PDF
            </a>
            <a href="{{ route('admin.reports.export.excel', request()->query()) }}"
               class="inline-flex items-center px-5 py-2.5 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 text-xs font-bold uppercase tracking-wider hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Export Excel
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <div class="flex items-center mb-6 pb-2 border-b border-gray-50">
            <div class="w-2 h-6 bg-blue-600 rounded-full mr-3"></div>
            <h3 class="text-lg font-bold text-gray-900">Filter Pencarian</h3>
        </div>
        <form method="GET" action="{{ route('admin.reports.attendance') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Pilih Kelas</label>
                <select name="class_id" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-medium">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                       class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-medium">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                       class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-medium">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Status Kehadiran</label>
                <select name="status" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-medium">
                    <option value="">Semua Status</option>
                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Alpha</option>
                    <option value="sick" {{ request('status') == 'sick' ? 'selected' : '' }}>Sakit</option>
                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                    <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Izin</option>
                </select>
            </div>
            <div class="lg:col-span-4 flex justify-end pt-4">
                <button type="submit" class="w-full md:w-auto px-10 py-3.5 bg-gray-900 text-white rounded-xl font-bold text-sm hover:bg-gray-800 transition-all shadow-lg shadow-gray-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Data</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $summary['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-orange-500 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Sakit</p>
                    <p class="text-3xl font-black text-orange-600 mt-1">{{ $summary['sick'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-emerald-500 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Hadir</p>
                    <p class="text-3xl font-black text-emerald-600 mt-1">{{ $summary['present'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-1000" style="width: {{ $summary['total'] > 0 ? ($summary['present'] / $summary['total']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-rose-500 transition-colors">
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
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-blue-500 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Persentase</p>
                    <p class="text-3xl font-black text-blue-600 mt-1">
                        {{ $summary['total'] > 0 ? round(($summary['present'] / $summary['total']) * 100, 1) : 0 }}%
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabel Data Presensi -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Data Rincian Presensi</h3>
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
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jam</th>
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
                                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-[10px] font-black shadow-sm mr-3">
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
                                    'sick' => 'bg-orange-50 text-orange-700 border-orange-100',
                                    'late' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'excused' => 'bg-sky-50 text-sky-700 border-sky-100'
                                ];
                                $statusLabels = ['present' => 'Hadir', 'absent' => 'Alpha', 'sick' => 'Sakit', 'late' => 'Terlambat', 'excused' => 'Izin'];
                                $style = $statusStyles[$attendance->status] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                            @endphp
                            <span class="px-3 py-1 text-[10px] font-black {{ $style }} rounded-lg border uppercase tracking-widest">
                                {{ $statusLabels[$attendance->status] ?? $attendance->status }}
                            </span>
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                            <div class="text-xs font-bold text-gray-600">{{ $attendance->check_in_time ?? '--:--' }}</div>
                        </td>
                        <td class="px-8 py-4 text-xs font-medium text-gray-500 italic max-w-xs truncate">
                            {{ $attendance->note ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Tidak Ada Data Presensi</h3>
                                <p class="text-gray-500 mt-1">Sesuaikan filter pencarian untuk menemukan data yang diinginkan.</p>
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