{{-- resources/views/guru/attendance/index.blade.php --}}
@extends('layouts.guru')

@section('title', 'Presensi Siswa')
@section('header', 'Presensi Siswa')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Presensi Siswa</h1>
            <p class="mt-2 text-base text-gray-500 max-w-2xl leading-relaxed">
                Kelola kehadiran siswa secara harian. Pilih kelas yang Anda ampu untuk memulai pengisian presensi.
            </p>
        </div>
    </div>

    <!-- Selection Section -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
        <div class="relative z-10 flex flex-col md:flex-row items-end gap-4">
            <div class="flex-1 space-y-2 w-full">
                <label for="class_id" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Pilih Kelas Mengajar</label>
                <form method="GET" action="{{ route('guru.attendance.index') }}" class="relative">
                    <select name="class_id" id="class_id" onchange="this.form.submit()" 
                            class="appearance-none block w-full pl-4 pr-10 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-bold text-gray-900 shadow-sm border border-gray-100/50">
                        <option value="">Pilih Kelas...</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </form>
            </div>
            
            <div class="w-full md:w-auto">
                <a href="{{ route('guru.attendance.index') }}" class="inline-flex items-center justify-center px-6 py-3.5 text-sm font-bold text-gray-400 hover:text-rose-600 transition-all">
                    Reset Filter
                </a>
            </div>
        </div>
    </div>

    @if($selectedClassId)
        @php
            $isSubmitted = $students->contains(function($s) {
                return $s->attendances->count() > 0;
            });
        @endphp
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ isLocked: {{ $isSubmitted ? 'true' : 'false' }} }">
            @if($isSubmitted)
            <div x-show="isLocked" x-cloak class="bg-blue-50 border-b border-blue-100 px-8 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-blue-900">Presensi Terkunci</h4>
                        <p class="text-xs text-blue-600">Anda sudah menyimpan presensi kelas ini untuk hari ini. Untuk mengubahnya, silakan buka kunci terlebih dahulu.</p>
                    </div>
                </div>
                <button type="button" @click="if(confirm('Apakah Anda yakin ingin mengubah presensi yang sudah tersimpan?')) isLocked = false" class="px-4 py-2 bg-white text-blue-600 rounded-lg text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors shadow-sm whitespace-nowrap ml-4">
                    Buka Kunci
                </button>
            </div>
            @endif

            <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Daftar Siswa</h3>
                    <p class="text-xs text-gray-500 font-medium">Silakan tentukan status kehadiran hari ini ({{ now()->translatedFormat('d F Y') }})</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" x-show="!isLocked" onclick="document.querySelectorAll('input[value=\'present\']').forEach(r => { r.checked = true; r.dispatchEvent(new Event('change')); });" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2 uppercase tracking-wider">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Tandai Semua Hadir
                    </button>
                    <div class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-blue-600 uppercase tracking-widest shadow-sm">
                        SESI AKTIF
                    </div>
                </div>
            </div>

            <form action="{{ route('guru.attendance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Identitas Siswa</th>
                                <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Kehadiran</th>
                                <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 bg-white">
                            @foreach($students as $student)
                                @php
                                    $currentAttendance = $student->attendances->first();
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center text-xs font-bold border border-gray-100/50 group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-all duration-300">
                                                {{ strtoupper(substr($student->name, 0, 1)) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $student->name }}</span>
                                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">{{ $student->nis }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-2.5" x-data="{ status: '{{ $currentAttendance->status ?? 'present' }}' }" :class="isLocked ? 'opacity-60 pointer-events-none grayscale-[50%]' : ''">
                                            <label class="cursor-pointer">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="present" x-model="status" class="hidden">
                                                <span :class="status === 'present' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all duration-300">Hadir</span>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="sick" x-model="status" class="hidden">
                                                <span :class="status === 'sick' ? 'bg-orange-500 text-white shadow-lg shadow-orange-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all duration-300">Sakit</span>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="excused" x-model="status" class="hidden">
                                                <span :class="status === 'excused' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all duration-300">Izin</span>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="late" x-model="status" class="hidden">
                                                <span :class="status === 'late' ? 'bg-amber-500 text-white shadow-lg shadow-amber-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all duration-300">Telat</span>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="absent" x-model="status" class="hidden">
                                                <span :class="status === 'absent' ? 'bg-rose-500 text-white shadow-lg shadow-rose-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all duration-300">Alpha</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <input type="text" name="attendance[{{ $student->id }}][note]" value="{{ $currentAttendance->note ?? '' }}" 
                                               :readonly="isLocked"
                                               :class="isLocked ? 'bg-gray-100/50 text-gray-400 cursor-not-allowed' : 'bg-gray-50 text-gray-600 focus:bg-white focus:ring-2 focus:ring-blue-500'"
                                               class="w-full border border-transparent rounded-xl px-4 py-2.5 text-xs font-medium placeholder:text-gray-300 transition-all shadow-sm"
                                               placeholder="Catatan...">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-8 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end" x-show="!isLocked" x-cloak>
                    <button type="submit" class="px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-bold shadow-xl shadow-blue-500/20 hover:scale-[1.02] transition-all duration-300 uppercase text-xs tracking-widest">
                        Simpan Presensi
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-white border border-gray-100 rounded-[2.5rem] p-24 text-center shadow-sm">
            <div class="w-24 h-24 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-8 text-gray-200 border-2 border-dashed border-gray-100">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Memilih Kelas</h3>
            <p class="text-gray-500 font-medium max-w-sm mx-auto leading-relaxed">Silakan pilih salah satu kelas mengajar Anda pada filter di atas untuk mulai mengelola kehadiran siswa hari ini.</p>
        </div>
    @endif
</div>
@endsection