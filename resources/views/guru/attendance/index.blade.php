{{-- resources/views/guru/attendance/index.blade.php --}}
@extends('layouts.guru')

@section('title', 'Presensi Siswa')
@section('header', 'Presensi Siswa')

@section('content')
<div class="space-y-6 sm:space-y-8 pb-8" x-data="{ isLocked: {{ $students->contains(fn($s) => $s->attendances->count() > 0) ? 'true' : 'false' }}, search: '' }">
    <!-- Header Section -->
    <div class="bg-white rounded-[2.5rem] p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-48 h-48 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
        <div class="relative flex flex-col md:flex-row md:items-start justify-between gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Presensi Siswa</h1>
                <p class="mt-2 text-xs sm:text-sm text-gray-500 font-medium max-w-2xl">
                    Kelola kehadiran siswa secara harian. Pilih kelas untuk memulai.
                </p>
            </div>
            <div class="flex items-center space-x-2 bg-gray-50 px-4 py-2 rounded-2xl">
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Sistem Aktif</span>
            </div>
        </div>
    </div>

    <!-- Selection Section -->
    <div class="bg-white p-5 sm:p-6 rounded-[2rem] border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('guru.attendance.index') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
            <div class="space-y-2">
                <label for="class_id" class="block text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih Kelas</label>
                <select name="class_id" id="class_id" onchange="this.form.submit()" 
                        class="block w-full px-4 py-3.5 bg-gray-50 border-none rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all text-xs font-black text-gray-900 shadow-inner">
                    <option value="">Pilih Kelas...</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-auto">
                <a href="{{ route('guru.attendance.index') }}" class="inline-flex items-center justify-center w-full px-8 py-3.5 text-xs font-black text-gray-500 hover:text-rose-600 transition-all uppercase tracking-widest">
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if($selectedClassId)
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden relative">
            
            <!-- Soft Block / Locked Banner -->
            <div x-show="isLocked" x-cloak class="bg-blue-600 px-8 py-4 flex items-center justify-between sticky top-0 z-20 shadow-lg">
                <div class="flex items-center gap-4 text-white">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black uppercase tracking-widest">Data Terkunci</h4>
                    </div>
                </div>
                <button type="button" @click="if(confirm('Buka kunci untuk mengubah presensi?')) isLocked = false" class="px-6 py-2.5 bg-white text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-50 transition-colors shadow-sm whitespace-nowrap ml-4">
                    Buka Kunci
                </button>
            </div>

            <!-- Toolbar -->
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <input type="text" x-model="search" placeholder="Cari siswa..." 
                       class="w-full sm:max-w-xs px-4 py-3 bg-white border-none rounded-2xl text-xs font-black shadow-sm">
                <button type="button" x-show="!isLocked" onclick="document.querySelectorAll('input[value=\'present\']').forEach(r => r.checked = true)" 
                        class="w-full sm:w-auto px-6 py-3 bg-white border border-gray-200 rounded-2xl text-[10px] font-black text-gray-700 hover:bg-emerald-600 hover:text-white transition-all shadow-sm uppercase tracking-widest">
                    Tandai Semua Hadir
                </button>
            </div>

            <form action="{{ route('guru.attendance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                
                <!-- Students Display -->
                <div class="divide-y divide-gray-50">
                    @foreach($students as $student)
                        @php $currentAttendance = $student->attendances->first(); @endphp
                        <div class="p-5 hover:bg-gray-50/50 transition-colors group" x-show='@json($student->name).toLowerCase().includes(search.toLowerCase())'>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center text-[10px] font-black border border-gray-100 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-gray-900">{{ $student->name }}</span>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">{{ $student->nis }}</span>
                                </div>
                            </div>
                            
                            <div class="flex flex-col gap-3" :class="isLocked ? 'opacity-60 pointer-events-none grayscale-[50%]' : ''" x-data="{ status: '{{ $currentAttendance->status ?? 'present' }}' }">
                                <div class="grid grid-cols-5 gap-2">
                                    @foreach(['present'=>'Hdr','sick'=>'Skt','excused'=>'Izn','late'=>'Tlt','absent'=>'Alp'] as $v => $l)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="attendance[{{ $student->id }}][status]" value="{{ $v }}" x-model="status" class="hidden">
                                            <span :class="status === '{{ $v }}' ? '{{ $v == 'present' ? 'bg-emerald-500' : ($v == 'sick' ? 'bg-orange-500' : ($v == 'excused' ? 'bg-blue-600' : ($v == 'late' ? 'bg-amber-500' : 'bg-rose-500'))) }} text-white shadow-lg' : 'bg-gray-100 text-gray-500'" 
                                                  class="block py-2 rounded-xl text-[9px] font-black text-center uppercase tracking-widest transition-all">{{ $l }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <input type="text" name="attendance[{{ $student->id }}][note]" value="{{ $currentAttendance->note ?? '' }}" 
                                       :readonly="isLocked" class="w-full bg-gray-50 border-none rounded-xl px-4 py-2.5 text-[10px] font-bold text-gray-700 placeholder:text-gray-400 shadow-sm"
                                       placeholder="Catatan...">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-8 py-8 border-t border-gray-50 bg-gray-50/30" x-show="!isLocked" x-cloak>
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:scale-[1.02] transition-all duration-300">
                        Simpan Presensi
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-white rounded-[2.5rem] p-24 text-center border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-400 italic">Pilih kelas untuk mulai presensi.</p>
        </div>
    @endif
</div>
@endsection
