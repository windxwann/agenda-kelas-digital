{{-- resources/views/walikelas/dashboard.blade.php --}}
@extends('layouts.walikelas')

@section('title', 'Dashboard Wali Kelas')
@section('header', 'Beranda')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-700 via-blue-600 to-indigo-800 rounded-3xl p-8 text-white shadow-xl shadow-indigo-500/20">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl"></div>
        
        <div class="relative flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-black tracking-tight">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                <p class="mt-2 text-lg text-indigo-100 font-medium max-w-xl">
                    @if($has_class)
                        Anda saat ini menjabat sebagai Wali Kelas untuk <span class="text-white font-bold">{{ $class->name }}</span>. 
                        Pantau perkembangan kelas binaan Anda secara real-time.
                    @else
                        Sistem belum mendeteksi kelas yang Anda ampu. Silakan hubungi Admin.
                    @endif
                </p>
                <div class="mt-6 flex gap-3">
                    <div class="px-4 py-2 bg-white/20 backdrop-blur-md rounded-xl text-xs font-bold uppercase tracking-wider">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="px-4 py-2 bg-emerald-500/20 backdrop-blur-md rounded-xl text-xs font-bold uppercase tracking-wider text-emerald-300 flex items-center">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></span>
                        Portal Aktif
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white/10 backdrop-blur-md rounded-3xl flex items-center justify-center border border-white/20 shadow-inner">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if($has_class)
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-indigo-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Siswa</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $total_students }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-500">
                <span class="font-bold text-indigo-600">{{ $class->name }}</span>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-emerald-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Hadir Hari Ini</p>
                    <p class="text-3xl font-black text-emerald-600 mt-1">{{ $today_attendance->get('present')?->count() ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-500">
                <span class="font-bold text-emerald-600">Terdaftar</span>
                <span class="mx-2">•</span>
                {{ $total_students }} Siswa
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-blue-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Izin / Sakit</p>
                    <p class="text-3xl font-black text-blue-600 mt-1">{{ ($today_attendance->get('excused')?->count() ?? 0) + ($today_attendance->get('sick')?->count() ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-500">
                Data kehadiran hari ini
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 group hover:border-rose-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanpa Ket. (Alpha)</p>
                    <p class="text-3xl font-black text-rose-600 mt-1">{{ $today_attendance->get('absent')?->count() ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center text-xs text-gray-500 text-rose-500 font-bold">
                Segera tindak lanjuti
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Agendas -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between p-6 border-b border-gray-50">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Agenda Kelas Terakhir</h3>
                    <p class="text-xs text-gray-500">Aktivitas belajar mengajar di kelas Anda</p>
                </div>
                <a href="{{ route('wali-kelas.agenda.index') }}" class="p-2 bg-gray-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mata Pelajaran</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Guru</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($latest_agendas as $agenda)
                        <tr class="group hover:bg-gray-50/50 transition-all">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $agenda->subject->name }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase mt-0.5">{{ $agenda->date->translatedFormat('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center text-[10px] font-bold shadow-sm">
                                        {{ strtoupper(substr($agenda->teacher->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $agenda->teacher->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-emerald-100">Terisi</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <p class="text-gray-400 text-sm italic">Belum ada agenda kelas terisi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions Sidebar -->
        <div class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 px-2">Aksi Cepat</h3>
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('wali-kelas.attendance.index') }}" class="group bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:border-indigo-500 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-gray-900 uppercase tracking-widest">Monitor Presensi</h4>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">Cek kehadiran harian siswa.</p>
                    </div>
                </a>

                <a href="{{ route('wali-kelas.export.attendance') }}" class="group bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:border-emerald-500 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-gray-900 uppercase tracking-widest">Unduh Rekap</h4>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">Export data ke Excel/PDF.</p>
                    </div>
                </a>

                <div class="bg-indigo-50 p-6 rounded-3xl border border-indigo-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-8 -mr-8 w-24 h-24 bg-indigo-100 rounded-full"></div>
                    <div class="relative z-10">
                        <h4 class="font-bold text-indigo-900 text-sm">Butuh Bantuan?</h4>
                        <p class="text-xs text-indigo-600 mt-1 leading-relaxed">Hubungi operator sekolah jika ada kendala data siswa atau kelas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
