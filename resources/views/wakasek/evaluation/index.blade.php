{{-- resources/views/wakasek/evaluation/index.blade.php --}}
@extends('layouts.wakasek')

@section('title', 'Evaluasi Akademik')
@section('header', 'Evaluasi Akademik')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header Section -->
    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-blue-50 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Evaluasi Belajar & Kurikulum</h1>
                <p class="mt-2 text-base text-gray-500 font-medium">
                    Analisis data kehadiran, performa guru, dan ketercapaian materi secara menyeluruh.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-5 py-2.5 bg-blue-50 text-blue-700 rounded-2xl text-xs font-bold uppercase tracking-widest border border-blue-100">
                    Bulan: {{ now()->translatedFormat('F Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Jurnal</p>
            <p class="text-3xl font-black text-gray-900">{{ $totalJournals }}</p>
            <div class="mt-4 flex items-center text-xs font-bold text-emerald-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Record Aktivitas
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Kepatuhan Jurnal</p>
            <p class="text-3xl font-black text-blue-600">{{ $teachingCompliance }}%</p>
            <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-blue-500 h-full" style="width: {{ $teachingCompliance }}%"></div>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Guru</p>
            <p class="text-3xl font-black text-gray-900">{{ $totalTeachers }}</p>
            <p class="mt-4 text-xs font-bold text-gray-400">Tenaga Pendidik Aktif</p>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Siswa</p>
            <p class="text-3xl font-black text-gray-900">{{ $totalStudents }}</p>
            <p class="mt-4 text-xs font-bold text-gray-400">Data Siswa Terdaftar</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Attendance Ranking -->
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Ranking Kehadiran Kelas</h3>
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-lg">Real-time</span>
            </div>
            <div class="p-8">
                <div class="space-y-6">
                    @foreach($classAttendance->take(5) as $class)
                    <div class="group">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl bg-gray-900 text-white flex items-center justify-center text-[10px] font-black italic">{{ $loop->iteration }}</span>
                                <span class="text-sm font-black text-gray-800 group-hover:text-blue-600 transition-colors">{{ $class['name'] }}</span>
                            </div>
                            <span class="text-sm font-black text-gray-900">{{ $class['percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-50 rounded-full h-3 border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $class['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Absence Breakdown -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8">
            <h3 class="text-lg font-black text-gray-900 tracking-tight mb-8">Detail Ketidakhadiran</h3>
            <div class="space-y-5">
                <div class="flex items-center justify-between p-4 bg-rose-50 rounded-2xl border border-rose-100/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-rose-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-rose-200">
                            <span class="font-black text-xs">A</span>
                        </div>
                        <span class="text-sm font-black text-rose-700">Alpha</span>
                    </div>
                    <span class="text-xl font-black text-rose-700">{{ $absenceStats['absent'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-orange-50 rounded-2xl border border-orange-100/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-200">
                            <span class="font-black text-xs">S</span>
                        </div>
                        <span class="text-sm font-black text-orange-700">Sakit</span>
                    </div>
                    <span class="text-xl font-black text-orange-700">{{ $absenceStats['sick'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-sky-50 rounded-2xl border border-sky-100/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-sky-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-sky-200">
                            <span class="font-black text-xs">I</span>
                        </div>
                        <span class="text-sm font-black text-sky-700">Izin</span>
                    </div>
                    <span class="text-xl font-black text-sky-700">{{ $absenceStats['excused'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-amber-50 rounded-2xl border border-amber-100/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-amber-200">
                            <span class="font-black text-xs">T</span>
                        </div>
                        <span class="text-sm font-black text-amber-700">Telat</span>
                    </div>
                    <span class="text-xl font-black text-amber-700">{{ $absenceStats['late'] ?? 0 }}</span>
                </div>
            </div>
            
            <p class="mt-8 text-[10px] font-bold text-gray-400 uppercase text-center tracking-widest leading-relaxed">
                Data diakumulasi berdasarkan laporan presensi seluruh kelas bulan ini.
            </p>
        </div>
    </div>

    <!-- Lower Section: Action & Insight -->
    <div class="bg-gray-900 rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-gray-900/20">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-500/10 rounded-full blur-[80px]"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-2xl font-black tracking-tight mb-2">Butuh Data Lebih Detail?</h3>
                <p class="text-gray-400 text-base font-medium max-w-lg">Anda dapat mengunduh laporan performa guru dan kehadiran siswa secara lengkap dalam format PDF atau Excel.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('wakasek-kurikulum.teaching.index') }}" class="px-8 py-4 bg-white text-gray-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-100 transition-all shadow-xl shadow-white/5">
                    Monitoring Guru
                </a>
                <a href="{{ route('wakasek-kurikulum.export.teaching') }}" class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/20">
                    Export Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
