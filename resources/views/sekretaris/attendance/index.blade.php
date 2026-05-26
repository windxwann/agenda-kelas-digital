{{-- resources/views/sekretaris/attendance/index.blade.php --}}
@extends('layouts.sekretaris')

@section('title', 'Input Presensi')
@section('header', 'Presensi Siswa')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Presensi Siswa</h1>
            <p class="text-gray-500 mt-1 font-medium text-sm">Input data kehadiran siswa harian berdasarkan kelas dan tanggal.</p>
        </div>
        <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-2xl border border-gray-100 shadow-sm">
            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Sistem Presensi Aktif</span>
        </div>
    </div>

    <!-- Selection Form -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <form method="GET" action="{{ route('sekretaris.attendance.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-8 items-end">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Kelas Anda</label>
                <div class="px-6 py-4 bg-gray-100 rounded-2xl text-sm font-bold text-gray-500 border border-transparent">
                    {{ $classes->first()->name }}
                </div>
                <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tanggal Presensi <span class="text-rose-500">*</span></label>
                <input type="date" name="date" value="{{ $date }}" required onchange="this.form.submit()"
                       class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-bold">
            </div>
            <div>
                <button type="submit" class="w-full px-10 py-4 text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl font-bold text-sm hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/20">
                    Muat Daftar Siswa
                </button>
            </div>
        </form>
    </div>

    @if($selectedClassId && count($students) > 0)
    <!-- Attendance Table -->
    <form action="{{ route('sekretaris.attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
        <input type="hidden" name="date" value="{{ $date }}">

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                <h3 class="text-lg font-bold text-gray-900">Daftar Siswa - {{ $classes->find($selectedClassId)->name }}</h3>
                <div class="flex items-center space-x-4">
                     <span class="px-4 py-1.5 bg-white text-[10px] font-bold text-gray-400 uppercase tracking-widest rounded-xl border border-gray-100">{{ count($students) }} Siswa</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50/30">
                            <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Siswa</th>
                            <th class="px-8 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Hadir</th>
                            <th class="px-8 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Izin</th>
                            <th class="px-8 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Alpha</th>
                            <th class="px-8 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Terlambat</th>
                            <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Keterangan / Note</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @foreach($students as $student)
                        @php 
                            $existing = $student->attendances->first();
                            $status = $existing ? $existing->status : 'present';
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-xs font-black shadow-sm mr-4">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $student->name }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">NIS: {{ $student->nis }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-center">
                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="present" 
                                       {{ $status == 'present' ? 'checked' : '' }}
                                       class="w-5 h-5 text-emerald-500 border-gray-300 focus:ring-emerald-500/20">
                            </td>
                            <td class="px-8 py-4 text-center">
                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="excused" 
                                       {{ $status == 'excused' ? 'checked' : '' }}
                                       class="w-5 h-5 text-blue-500 border-gray-300 focus:ring-blue-500/20">
                            </td>
                            <td class="px-8 py-4 text-center">
                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="absent" 
                                       {{ $status == 'absent' ? 'checked' : '' }}
                                       class="w-5 h-5 text-rose-500 border-gray-300 focus:ring-rose-500/20">
                            </td>
                            <td class="px-8 py-4 text-center">
                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="late" 
                                       {{ $status == 'late' ? 'checked' : '' }}
                                       class="w-5 h-5 text-amber-500 border-gray-300 focus:ring-amber-500/20">
                            </td>
                            <td class="px-8 py-4">
                                <input type="text" name="attendance[{{ $student->id }}][note]" 
                                       value="{{ $existing ? $existing->note : '' }}"
                                       placeholder="Tambahkan catatan..."
                                       class="w-full px-4 py-2 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-xs font-medium">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-8 bg-gray-50/50 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center space-x-2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs font-bold uppercase tracking-widest">Pastikan semua data sudah benar sebelum menyimpan.</p>
                </div>
                <button type="submit" class="w-full md:w-auto px-12 py-4 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg shadow-blue-500/20 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl transition-all duration-200">
                    Simpan Presensi Kelas
                </button>
            </div>
        </div>
    </form>
    @elseif($selectedClassId)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-20 text-center">
        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Tidak Ada Siswa</h3>
        <p class="text-gray-500 mt-2 font-medium">Belum ada siswa yang terdaftar di kelas ini.</p>
    </div>
    @else
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-20 text-center">
        <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Pilih Kelas & Tanggal</h3>
        <p class="text-gray-500 mt-2 font-medium">Silakan pilih kelas dan tanggal untuk mulai menginput presensi.</p>
    </div>
    @endif
</div>
@endsection
