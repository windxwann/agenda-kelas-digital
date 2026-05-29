{{-- resources/views/siswa/agenda/index.blade.php --}}
@extends('layouts.siswa')

@section('title', 'Agenda Kelas')
@section('header', 'Agenda Kelas')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Agenda Kelas</h1>
            <p class="mt-2 text-base text-gray-500 max-w-2xl leading-relaxed">
                Pantau seluruh materi, tugas, dan kegiatan pembelajaran di kelas Anda secara real-time.
            </p>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-8">
        <form method="GET" action="{{ route('siswa.agenda.index') }}" class="flex flex-col lg:flex-row gap-4">
            <!-- Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari materi atau kegiatan..." 
                       class="block w-full pl-12 pr-4 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Subject Filter -->
                <div class="w-full sm:w-56">
                    <select name="subject_id" onchange="this.form.submit()"
                            class="block w-full px-4 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-semibold">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Academic Year Filter -->
                <div class="w-full sm:w-56">
                    <select name="academic_year_id" onchange="this.form.submit()"
                            class="block w-full px-4 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-semibold">
                        <option value="">Tahun Ajaran (Aktif)</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Button -->
                <button type="submit" 
                        class="inline-flex items-center justify-center px-6 py-3.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all duration-200">
                    Cari
                </button>

                <!-- Reset Button -->
                @if(request()->anyFilled(['search', 'subject_id', 'academic_year_id']))
                    <a href="{{ route('siswa.agenda.index') }}" 
                       class="inline-flex items-center justify-center px-4 py-3.5 text-sm font-semibold text-red-500 hover:bg-red-50 rounded-xl transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    {{-- Agenda Timeline --}}
    <div class="relative max-w-4xl mx-auto">
        
        @if($agendas->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Tidak Ada Agenda</h3>
                <p class="text-sm text-gray-500 mt-1">Belum ada agenda atau tugas yang diposting.</p>
            </div>
        @else
            <!-- Timeline Line -->
            <div class="absolute left-8 md:left-24 top-0 bottom-0 w-0.5 bg-gray-200"></div>

            <div class="space-y-12">
                @foreach($groupedAgendas as $date => $dailyAgendas)
                    <div class="relative flex items-start">
                        <!-- Date Marker -->
                        <div class="absolute left-0 md:left-4 w-16 md:w-40 flex justify-end pr-8">
                            <div class="bg-white py-1.5 px-3 rounded-xl shadow-sm border border-gray-100 text-xs font-bold text-gray-600 relative z-10 whitespace-nowrap">
                                @if($date === '')
                                    <span class="text-gray-500">Tanpa Tanggal</span>
                                @elseif($date === $todayStr)
                                    <span class="text-blue-600">Hari Ini</span>
                                @elseif($date === $yesterdayStr)
                                    <span class="text-gray-500">Kemarin</span>
                                @else
                                    @php
                                        $months = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                                        [$y, $m, $d] = explode('-', $date);
                                        echo (int)$d . ' ' . ($months[(int)$m] ?? $m) . ' ' . $y;
                                    @endphp
                                @endif
                            </div>
                            <!-- Connector Dot -->
                            <div class="absolute right-[5px] top-1/2 -translate-y-1/2 w-3 h-3 bg-blue-500 rounded-full border-2 border-white shadow-sm z-10"></div>
                        </div>
                        
                        <!-- Cards Container -->
                        <div class="ml-24 md:ml-48 flex-1 space-y-4">
                            @foreach($dailyAgendas as $agenda)
                                <!-- Timeline Card -->
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer group flex flex-col sm:flex-row gap-5" onclick="openAgendaModal({{ $agenda->id }})">
                                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex-shrink-0 flex items-center justify-center font-bold text-lg hidden sm:flex">
                                        {{ strtoupper(substr($agenda->subject->name ?? 'G', 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">
                                                {{ $agenda->subject->name ?? 'Umum' }}
                                            </span>
                                            <span class="text-xs font-medium text-gray-400">
                                                Oleh: {{ $agenda->teacher->name ?? '-' }}
                                            </span>
                                        </div>
                                        <h3 class="text-base font-bold text-gray-900 group-hover:text-blue-600 transition-colors mb-1.5 truncate">
                                            {{ $agenda->title }}
                                        </h3>
                                        <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                            {{ strip_tags($agenda->description) }}
                                        </p>
                                        
                                        @if($agenda->attachments)
                                            <div class="mt-4 flex items-center">
                                                <div class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-lg group-hover:bg-blue-50 group-hover:border-blue-100 transition-colors">
                                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                    </svg>
                                                    <span class="text-[10px] font-bold text-gray-600 group-hover:text-blue-700">Lampiran Tersedia</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination --}}
            @if($agendas->hasPages())
                <div class="mt-12 flex justify-center ml-24 md:ml-48">
                    {{ $agendas->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

{{-- Modal Detail Agenda --}}
<div id="agendaModal" 
     class="fixed inset-0 z-[60] hidden overflow-y-auto">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" 
         onclick="closeAgendaModal()"></div>
    
    {{-- Modal Box --}}
    <div class="flex items-center justify-center min-h-screen p-4 relative pointer-events-none">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all pointer-events-auto">
            {{-- Modal Header with Subject Banner --}}
            <div id="modalBanner" class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700 p-8 flex items-end relative">
                <button onclick="closeAgendaModal()" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="text-white">
                    <span id="modalSubject" class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-[10px] font-bold uppercase tracking-wider mb-2 inline-block text-white"></span>
                    <h3 id="modalTitle" class="text-2xl font-bold leading-tight line-clamp-1"></h3>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="p-8">
                {{-- Meta Info --}}
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Tanggal</p>
                            <p id="modalDate" class="text-sm font-bold text-gray-800"></p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Guru</p>
                            <p id="modalTeacher" class="text-sm font-bold text-gray-800"></p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Kelas</p>
                            <p id="modalClass" class="text-sm font-bold text-gray-800"></p>
                        </div>
                    </div>

                    <div id="modalRoomWrapper" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Ruangan</p>
                            <p id="modalRoom" class="text-sm font-bold text-gray-800"></p>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="bg-gray-50 rounded-2xl p-6 mb-8">
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-3 tracking-widest">Deskripsi / Materi</p>
                    <div id="modalDescription" class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap"></div>
                </div>

                {{-- Attachment Section --}}
                <div id="attachmentSection" class="hidden">
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-2xl border border-blue-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-blue-600 font-bold uppercase">Berkas Terlampir</p>
                                <p class="text-sm font-bold text-gray-900">Materi & Tugas Terkait</p>
                            </div>
                        </div>
                        <a id="modalAttachmentBtn" href="#" target="_blank" 
                           class="px-5 py-2 bg-white text-blue-600 rounded-xl text-xs font-bold shadow-sm hover:bg-blue-600 hover:text-white transition-all">
                            Unduh Berkas
                        </a>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="mt-10 flex justify-end">
                    <button onclick="closeAgendaModal()" 
                            class="px-8 py-3 bg-gray-100 text-gray-700 rounded-2xl text-sm font-bold hover:bg-gray-200 transition-all">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openAgendaModal(id) {
        const modal = document.getElementById('agendaModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Lock scroll
        
        // Show loading state or reset
        document.getElementById('modalTitle').innerText = 'Memuat...';
        document.getElementById('modalSubject').innerText = '...';
        document.getElementById('modalDate').innerText = '...';
        document.getElementById('modalTeacher').innerText = '...';
        document.getElementById('modalClass').innerText = '...';
        document.getElementById('modalRoom').innerText = '...';
        document.getElementById('modalRoomWrapper').classList.add('hidden');
        document.getElementById('modalDescription').innerText = 'Harap tunggu...';
        document.getElementById('attachmentSection').classList.add('hidden');
        
        // Fetch agenda detail
        fetch(`{{ url('siswa/agenda') }}/${id}/json`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalTitle').innerText = data.title;
                document.getElementById('modalSubject').innerText = data.subject_name;
                document.getElementById('modalDate').innerText = data.date;
                document.getElementById('modalTeacher').innerText = data.teacher_name;
                document.getElementById('modalClass').innerText = data.class_name;
                document.getElementById('modalDescription').innerHTML = data.description;
                
                if (data.room) {
                    document.getElementById('modalRoom').innerText = data.room;
                    document.getElementById('modalRoomWrapper').classList.remove('hidden');
                }
                
                if (data.attachments) {
                    document.getElementById('attachmentSection').classList.remove('hidden');
                    document.getElementById('modalAttachmentBtn').href = data.attachments;
                }
            })
            .catch(error => {
                console.error('Error fetching agenda:', error);
                document.getElementById('modalTitle').innerText = 'Error';
                document.getElementById('modalDescription').innerText = 'Gagal memuat data agenda.';
            });
    }
    
    function closeAgendaModal() {
        const modal = document.getElementById('agendaModal');
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scroll
    }

    // Close on Escape
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAgendaModal();
        }
    });
</script>
@endpush