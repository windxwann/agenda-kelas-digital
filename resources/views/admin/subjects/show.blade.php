{{-- resources/views/admin/subjects/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Mata Pelajaran - ' . $subject->name)
@section('header', 'Detail Mata Pelajaran')

@section('content')
<div id="subject-detail-content" class="pb-12">
    <!-- Breadcrumb & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <nav class="flex text-sm text-gray-500 mb-2">
                <a href="{{ route('admin.subjects.index') }}" class="hover:text-indigo-600 transition-colors">Mata Pelajaran</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900 font-medium">Detail Mata Pelajaran</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">{{ $subject->name }}</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.subjects.edit', $subject) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Mapel
            </a>
            <a href="{{ route('admin.subjects.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-semibold rounded-xl border border-gray-200 hover:bg-gray-50 transition-all">
                Kembali
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-teal-500 to-emerald-600 px-6 py-8 flex flex-col items-center">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg mb-4">
                        <span class="text-4xl font-bold text-teal-600">
                            {{ strtoupper(substr($subject->name, 0, 1)) }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold text-white text-center">{{ $subject->name }}</h2>
                    <p class="text-teal-100 mt-1 font-mono bg-white/20 px-3 py-1 rounded-lg">{{ $subject->code }}</p>
                </div>
                
                <div class="p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">Informasi Guru Pengampu</h3>
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-sm">
                            {{ strtoupper(substr($subject->teacher->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-gray-900 truncate">{{ $subject->teacher->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $subject->teacher->email ?? '-' }}</p>
                        </div>
                    </div>
                    
                    @if($subject->description)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Deskripsi</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $subject->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Statistics Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Statistik</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-center">
                        <p class="text-2xl font-black text-blue-600">{{ $stats['total_classes'] }}</p>
                        <p class="text-[10px] font-bold text-blue-800 uppercase tracking-wider mt-1">Kelas</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 border border-green-100 text-center">
                        <p class="text-2xl font-black text-green-600">{{ $stats['total_schedules'] }}</p>
                        <p class="text-[10px] font-bold text-green-800 uppercase tracking-wider mt-1">Jadwal</p>
                    </div>
                    <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-100 text-center">
                        <p class="text-2xl font-black text-yellow-600">{{ $stats['total_agendas'] }}</p>
                        <p class="text-[10px] font-bold text-yellow-800 uppercase tracking-wider mt-1">Total Agenda</p>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-4 border border-purple-100 text-center">
                        <p class="text-2xl font-black text-purple-600">{{ $subject->credit_hours }}</p>
                        <p class="text-[10px] font-bold text-purple-800 uppercase tracking-wider mt-1">JP/Minggu</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Jadwal Mengajar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900">Jadwal Mengajar</h3>
                    <a href="{{ route('admin.schedules.create', ['subject_id' => $subject->id]) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                        + Tambah Jadwal
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    @php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                        $dayNames = [
                            'Monday' => 'Senin',
                            'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday' => 'Kamis',
                            'Friday' => 'Jumat'
                        ];
                    @endphp
                    
                    @if($subject->schedules->count() > 0)
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Hari</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jam</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ruang</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($subject->schedules->sortBy(['day', 'start_time']) as $schedule)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $dayNames[$schedule->day] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                        {{ $schedule->class->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $schedule->room ?? '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm text-gray-500">Belum ada jadwal mengajar</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Daftar Kelas yang Mengajar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Daftar Kelas yang Diajar</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tingkat</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Wali Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah Siswa</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($subject->schedules->unique('class_id') as $schedule)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    {{ $schedule->class->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-xs font-bold bg-blue-50 text-blue-700 rounded-lg border border-blue-100">
                                        Kelas {{ $schedule->class->grade_level }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $schedule->class->homeroomTeacher->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                    {{ $schedule->class->students_count ?? $schedule->class->students->count() }} siswa
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <p class="text-sm">Belum ada kelas yang mengajar mata pelajaran ini</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection