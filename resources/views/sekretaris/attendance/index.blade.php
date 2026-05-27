{{-- resources/views/sekretaris/attendance/index.blade.php --}}
@extends('layouts.sekretaris')

@section('title', 'Presensi Siswa')
@section('header', 'Presensi Siswa')

@section('content')
@php
    $isSubmitted = isset($students) && $students->contains(function($s) {
        return $s->attendances->count() > 0;
    });
@endphp
<div class="space-y-8 pb-8" x-data="attendanceManager()">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Presensi Siswa</h1>
            <p class="mt-2 text-base text-gray-500 max-w-2xl leading-relaxed">
                Kelola kehadiran siswa kelas <strong>{{ $classes->first()->name }}</strong> secara harian.
            </p>
        </div>
        <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-2xl border border-gray-100 shadow-sm">
            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Sistem Presensi Aktif</span>
        </div>
    </div>

    <!-- Selection Section -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
        <form method="GET" action="{{ route('sekretaris.attendance.index') }}" class="relative z-10 flex flex-col md:flex-row items-end gap-4">
            <div class="w-full md:w-48 space-y-2">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Kelas Anda</label>
                <div class="px-4 py-3.5 bg-gray-100 border border-gray-100/50 rounded-xl text-sm font-bold text-gray-500 shadow-sm">
                    {{ $classes->first()->name }}
                </div>
            </div>
            
            <div class="flex-1 space-y-2 w-full">
                <label for="date" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Pilih Tanggal Presensi</label>
                <input type="date" name="date" id="date" value="{{ $date }}" onchange="this.form.submit()" 
                        class="appearance-none block w-full px-4 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-bold text-gray-900 shadow-sm border border-gray-100/50">
            </div>
            
            <div class="w-full md:w-auto">
                <button type="submit" class="inline-flex items-center justify-center w-full px-8 py-3.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 uppercase tracking-widest">
                    Muat Data
                </button>
            </div>
        </form>
    </div>

    @if($selectedClassId)
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden relative">
            
            <!-- Soft Block / Locked Banner -->
            <template x-if="isLocked">
                <div x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="-translate-y-full"
                     x-transition:enter-end="translate-y-0"
                     class="bg-blue-600 px-8 py-4 flex items-center justify-between sticky top-0 z-20 shadow-lg">
                    <div class="flex items-center gap-4 text-white">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-black uppercase tracking-widest">Data Presensi Terkunci</h4>
                            <p class="text-[11px] font-medium opacity-90">Sistem mengunci input untuk mencegah perubahan tidak sengaja.</p>
                        </div>
                    </div>
                    <button type="button" @click="unlock()" class="px-6 py-2.5 bg-white text-blue-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-50 transition-colors shadow-sm whitespace-nowrap ml-4">
                        Buka Kunci
                    </button>
                </div>
            </template>

            <!-- Real-time Stats Bar -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-px bg-gray-100 border-b border-gray-100">
                <div class="bg-white px-8 py-6 flex flex-col items-center justify-center group hover:bg-emerald-50 transition-colors">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest group-hover:text-emerald-500 transition-colors">Hadir</span>
                    <span class="text-3xl font-black text-gray-900 mt-1" x-text="stats.present">0</span>
                </div>
                <div class="bg-white px-8 py-6 flex flex-col items-center justify-center group hover:bg-orange-50 transition-colors">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest group-hover:text-orange-500 transition-colors">Sakit</span>
                    <span class="text-3xl font-black text-gray-900 mt-1" x-text="stats.sick">0</span>
                </div>
                <div class="bg-white px-8 py-6 flex flex-col items-center justify-center group hover:bg-blue-50 transition-colors">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest group-hover:text-blue-500 transition-colors">Izin</span>
                    <span class="text-3xl font-black text-gray-900 mt-1" x-text="stats.excused">0</span>
                </div>
                <div class="bg-white px-8 py-6 flex flex-col items-center justify-center group hover:bg-amber-50 transition-colors">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest group-hover:text-amber-500 transition-colors">Telat</span>
                    <span class="text-3xl font-black text-gray-900 mt-1" x-text="stats.late">0</span>
                </div>
                <div class="bg-white px-8 py-6 flex flex-col items-center justify-center group hover:bg-rose-50 transition-colors">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest group-hover:text-rose-500 transition-colors">Alpha</span>
                    <span class="text-3xl font-black text-gray-900 mt-1" x-text="stats.absent">0</span>
                </div>
            </div>

            <!-- Toolbar (Search & Mark All) -->
            <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" x-model="search" placeholder="Cari nama siswa..." 
                           class="block w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all shadow-sm">
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" x-show="!isLocked" @click="markAllPresent()" 
                            class="px-6 py-3 bg-white border border-gray-200 rounded-2xl text-[10px] font-black text-gray-700 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all shadow-sm flex items-center gap-2 uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Tandai Semua Hadir
                    </button>
                    <div class="px-5 py-3 bg-blue-50 text-blue-600 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-blue-100">
                        {{ \Carbon\Carbon::parse($date)->isToday() ? 'SESI AKTIF' : 'RIWAYAT' }}
                    </div>
                </div>
            </div>

            <form action="{{ route('sekretaris.attendance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                <input type="hidden" name="date" value="{{ $date }}">
                
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
                                <tr class="hover:bg-gray-50/50 transition-colors group" 
                                    x-show="shouldShow('{{ addslashes($student->name) }}')">
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
                                        <div class="flex items-center gap-2.5" 
                                             x-data="{ 
                                                status: '{{ $currentAttendance->status ?? 'present' }}',
                                                init() { 
                                                    this.$watch('status', (val, old) => {
                                                        $dispatch('update-stats', { newStatus: val, oldStatus: old });
                                                    });
                                                    // Initial stats calculation
                                                    $dispatch('register-student', { status: this.status });
                                                }
                                             }" 
                                             :class="isLocked ? 'opacity-60 pointer-events-none grayscale-[50%]' : ''">
                                            
                                            <template x-for="opt in [
                                                {v: 'present', l: 'Hadir', c: 'bg-emerald-500'},
                                                {v: 'sick', l: 'Sakit', c: 'bg-orange-500'},
                                                {v: 'excused', l: 'Izin', c: 'bg-blue-600'},
                                                {v: 'late', l: 'Telat', c: 'bg-amber-500'},
                                                {v: 'absent', l: 'Alpha', c: 'bg-rose-500'}
                                            ]">
                                                <label class="cursor-pointer">
                                                    <input type="radio" 
                                                           name="attendance[{{ $student->id }}][status]" 
                                                           :value="opt.v" 
                                                           x-model="status" 
                                                           class="hidden">
                                                    <span :class="status === opt.v ? opt.c + ' text-white shadow-lg' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'" 
                                                          class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all duration-300"
                                                          x-text="opt.l"></span>
                                                </label>
                                            </template>
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
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Kelas Tidak Ditemukan</h3>
            <p class="text-gray-500 font-medium max-w-sm mx-auto leading-relaxed">Anda belum terdaftar di kelas manapun. Silakan hubungi admin untuk pengaturan kelas.</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function attendanceManager() {
        return {
            isLocked: {{ $isSubmitted ? 'true' : 'false' }},
            search: '',
            stats: {
                present: 0,
                sick: 0,
                excused: 0,
                late: 0,
                absent: 0
            },
            
            init() {
                this.$on('update-stats', (e) => {
                    this.stats[e.detail.oldStatus]--;
                    this.stats[e.detail.newStatus]++;
                });
                this.$on('register-student', (e) => {
                    this.stats[e.detail.status]++;
                });
            },

            unlock() {
                if(confirm('Apakah Anda yakin ingin mengubah presensi yang sudah tersimpan?')) {
                    this.isLocked = false;
                }
            },

            shouldShow(name) {
                if (!this.search) return true;
                return name.toLowerCase().includes(this.search.toLowerCase());
            },

            markAllPresent() {
                const radios = document.querySelectorAll('input[value="present"]');
                radios.forEach(radio => {
                    if (!radio.checked) {
                        radio.click();
                    }
                });
            }
        }
    }
</script>
@endpush
@endsection
