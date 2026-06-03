{{-- resources/views/sekretaris/attendance/report.blade.php --}}
@extends('layouts.sekretaris')

@section('title', 'Laporan Presensi')
@section('header', 'Laporan Presensi Siswa')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Laporan Presensi</h1>
            <p class="text-gray-500 mt-1 font-medium text-xs sm:text-sm">Analisis data kehadiran siswa di kelas {{ $class->name ?? '' }}</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('sekretaris.print.attendance', request()->query()) }}" target="_blank"
               class="inline-flex items-center px-6 py-3 bg-rose-50 text-rose-700 rounded-2xl border border-rose-100 text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 sm:p-8">
        <div class="flex items-center mb-6 pb-2 border-b border-gray-50">
            <div class="w-2 h-6 bg-blue-600 rounded-full mr-3"></div>
            <h3 class="text-base sm:text-lg font-bold text-gray-900">Filter Pencarian</h3>
        </div>
        <form method="GET" action="{{ route('sekretaris.attendance.report') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div class="space-y-2">
                <label class="text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                       class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all text-xs sm:text-sm font-bold shadow-inner">
            </div>
            <div class="space-y-2">
                <label class="text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                       class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all text-xs sm:text-sm font-bold shadow-inner">
            </div>
            <div class="flex items-end lg:col-span-1">
                <button type="submit" class="w-full px-10 py-3.5 bg-gray-900 text-white rounded-2xl font-black text-xs sm:text-sm hover:bg-black transition-all shadow-lg shadow-gray-200 flex items-center justify-center uppercase tracking-widest">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>
    
    <!-- Summary Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:flex lg:flex-wrap items-center gap-3 sm:gap-6 bg-white p-5 sm:p-6 rounded-[2.5rem] border border-gray-100 shadow-sm">
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-1.5 h-6 bg-gray-900 rounded-full"></div>
            <div>
                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Total</p>
                <p class="text-sm font-black text-gray-900 leading-none">{{ $summary['total'] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
            <div>
                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Hadir</p>
                <p class="text-sm font-black text-emerald-600 leading-none">{{ $summary['present'] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-1.5 h-6 bg-rose-500 rounded-full"></div>
            <div>
                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Alpa</p>
                <p class="text-sm font-black text-rose-600 leading-none">{{ $summary['absent'] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-1.5 h-6 bg-orange-500 rounded-full"></div>
            <div>
                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Sakit</p>
                <p class="text-sm font-black text-orange-600 leading-none">{{ $summary['sick'] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-1.5 h-6 bg-sky-500 rounded-full"></div>
            <div>
                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Izin</p>
                <p class="text-sm font-black text-sky-600 leading-none">{{ $summary['excused'] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
            <div>
                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Telat</p>
                <p class="text-sm font-black text-amber-600 leading-none">{{ $summary['late'] }}</p>
            </div>
        </div>
    </div>
    
    <!-- Presensi Display -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <h3 class="text-sm sm:text-lg font-black text-gray-900">Ringkasan Presensi</h3>
            <span class="px-3 py-1.5 bg-white text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] rounded-xl border border-gray-100 shadow-sm">{{ $students->total() }} Siswa</span>
        </div>
        
        {{-- Desktop View --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-50">
                <thead>
                    <tr class="bg-gray-50/20">
                        <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Siswa</th>
                        <th class="px-8 py-5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kelas</th>
                        <th class="px-8 py-5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Hadir</th>
                        <th class="px-8 py-5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Alpa</th>
                        <th class="px-8 py-5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sakit</th>
                        <th class="px-8 py-5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Izin</th>
                        <th class="px-8 py-5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Telat</th>
                        <th class="px-8 py-5 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Opsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-[10px] font-black shadow-sm mr-4 group-hover:scale-110 transition-transform">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-black text-gray-900 truncate">{{ $student->name }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">NIS: {{ $student->nis }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-sm font-bold text-gray-600">
                            {{ $student->class->name ?? '-' }}
                        </td>
                        <td class="px-8 py-5 text-center text-sm font-black text-emerald-600">{{ $student->attendances->where('status', 'present')->count() }}</td>
                        <td class="px-8 py-5 text-center text-sm font-black text-rose-600">{{ $student->attendances->where('status', 'absent')->count() }}</td>
                        <td class="px-8 py-5 text-center text-sm font-black text-orange-600">{{ $student->attendances->where('status', 'sick')->count() }}</td>
                        <td class="px-8 py-5 text-center text-sm font-black text-sky-600">{{ $student->attendances->where('status', 'excused')->count() }}</td>
                        <td class="px-8 py-5 text-center text-sm font-black text-amber-600">{{ $student->attendances->where('status', 'late')->count() }}</td>
                        <td class="px-8 py-5 text-right whitespace-nowrap">
                            <button onclick="viewStudentAttendance({{ $student->id }}, '{{ $student->name }}')" 
                                    class="px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all shadow-sm">
                                Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-8 py-20 text-center text-gray-400 font-medium italic">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile View (Cards) --}}
        <div class="lg:hidden divide-y divide-gray-50">
            @forelse($students as $student)
            <div class="p-5 space-y-4 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-600 text-white rounded-2xl flex items-center justify-center text-[10px] font-black shadow-lg">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-gray-900 leading-tight">{{ $student->name }}</span>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">NIS: {{ $student->nis }}</span>
                        </div>
                    </div>
                    <button onclick="viewStudentAttendance({{ $student->id }}, '{{ $student->name }}')" 
                            class="px-4 py-2 bg-gray-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-sm">
                        Detail
                    </button>
                </div>
                
                <div class="bg-gray-50/50 rounded-2xl p-4 grid grid-cols-3 gap-y-4 gap-x-2">
                    <div class="flex flex-col">
                        <span class="text-[7px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Hadir</span>
                        <span class="text-xs font-black text-emerald-600">{{ $student->attendances->where('status', 'present')->count() }} Hari</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[7px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Alpa</span>
                        <span class="text-xs font-black text-rose-600">{{ $student->attendances->where('status', 'absent')->count() }} Hari</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[7px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Sakit</span>
                        <span class="text-xs font-black text-orange-600">{{ $student->attendances->where('status', 'sick')->count() }} Hari</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[7px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Izin</span>
                        <span class="text-xs font-black text-sky-600">{{ $student->attendances->where('status', 'excused')->count() }} Hari</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[7px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Telat</span>
                        <span class="text-xs font-black text-amber-600">{{ $student->attendances->where('status', 'late')->count() }} Hari</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-10 text-center text-gray-400 italic">Data tidak ditemukan.</div>
            @endforelse
        </div>
        
        @if($students->hasPages())
        <div class="px-6 sm:px-8 py-5 sm:py-6 border-t border-gray-50 bg-gray-50/30">
            {{ $students->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Detail Presensi - More Mobile Friendly -->
<div id="attendanceModal" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm hidden items-end sm:items-center justify-center z-50 transition-all duration-300">
    <div class="bg-white rounded-t-[2.5rem] sm:rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden border border-white/20 animate-slide-up sm:animate-fade-in">
        <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-gray-100 flex items-center justify-between bg-white">
            <div class="flex flex-col">
                <h3 class="text-base sm:text-lg font-black text-gray-900" id="modalTitle">Detail Presensi</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5" id="modalSubtitle"></p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="closeModal()" class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-50 text-gray-400 hover:text-gray-600 rounded-full flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
        <div class="px-6 sm:px-8 py-5 sm:py-6">
            <div class="mb-5">
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1 mb-2 block">Pilih Bulan</label>
                <select id="monthFilter" onchange="loadStudentData()" class="w-full bg-gray-50 border-none rounded-2xl text-xs sm:text-sm font-bold text-gray-600 px-4 py-3 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-inner">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="max-h-[50vh] overflow-y-auto pr-1 custom-scrollbar">
                <table class="min-w-full divide-y divide-gray-50">
                    <thead class="sticky top-0 bg-white">
                        <tr>
                            <th class="px-2 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                            <th class="px-2 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-2 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Ket</th>
                        </tr>
                    </thead>
                    <tbody id="modalBody" class="divide-y divide-gray-50">
                        <!-- Data dimuat via JS -->
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Handle bar for mobile --}}
        <div class="h-6 sm:hidden bg-white flex items-center justify-center pb-2">
            <div class="w-12 h-1 bg-gray-100 rounded-full"></div>
        </div>
    </div>
</div>

<style>
    @keyframes slide-up {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    @keyframes fade-in {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-slide-up { animation: slide-up 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .animate-fade-in { animation: fade-in 0.2s ease-out; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    let currentStudentId = null;
    let currentStudentName = '';

    async function viewStudentAttendance(studentId, studentName) {
        currentStudentId = studentId;
        currentStudentName = studentName;
        loadStudentData();
    }

    async function loadStudentData() {
        document.getElementById('modalTitle').innerText = 'Riwayat Presensi';
        document.getElementById('modalSubtitle').innerText = currentStudentName;
        const month = document.getElementById('monthFilter').value;
        const modalBody = document.getElementById('modalBody');
        
        modalBody.innerHTML = '<tr><td colspan="3" class="text-center py-8 text-[10px] font-bold text-gray-400 uppercase tracking-widest italic">Memuat data...</td></tr>';
        document.getElementById('attendanceModal').classList.remove('hidden');
        document.getElementById('attendanceModal').classList.add('flex');

        try {
            const response = await fetch(`/sekretaris/attendance/report/student/${currentStudentId}?month=${month}&year={{ date('Y') }}`);
            const student = await response.json();
            
            modalBody.innerHTML = '';
            if (student.attendances.length === 0) {
                modalBody.innerHTML = '<tr><td colspan="3" class="text-center py-10 text-xs font-medium text-gray-400">Tidak ada riwayat presensi di bulan ini.</td></tr>';
            } else {
                const statusLabels = {'present': 'Hadir', 'absent': 'Alpha', 'sick': 'Sakit', 'late': 'Telat', 'excused': 'Izin'};
                const statusColors = {
                    'present': 'text-emerald-600',
                    'absent': 'text-rose-600',
                    'sick': 'text-orange-600',
                    'late': 'text-amber-600',
                    'excused': 'text-sky-600'
                };
                student.attendances.forEach(att => {
                    modalBody.innerHTML += `
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-2 py-4 text-xs text-gray-900 font-bold">${att.date}</td>
                            <td class="px-2 py-4 text-xs font-black ${statusColors[att.status] || 'text-gray-700'}">${statusLabels[att.status] || att.status}</td>
                            <td class="px-2 py-4 text-[10px] text-gray-500 font-medium italic">${att.note || '-'}</td>
                        </tr>
                    `;
                });
            }
        } catch (error) {
            modalBody.innerHTML = '<tr><td colspan="3" class="text-center py-8 text-rose-500 font-black uppercase text-[10px]">Gagal memuat data.</td></tr>';
        }
    }

    function closeModal() {
        document.getElementById('attendanceModal').classList.add('hidden');
        document.getElementById('attendanceModal').classList.remove('flex');
    }
</script>
@endsection
