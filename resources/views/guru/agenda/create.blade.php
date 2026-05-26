{{-- resources/views/guru/agenda/create.blade.php --}}
@extends('layouts.guru')

@section('title', 'Buat Jurnal')
@section('header', 'Jurnal Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Catat Aktivitas</h1>
            <p class="mt-2 text-base text-gray-500 max-w-2xl leading-relaxed">
                Silakan isi detail kegiatan belajar mengajar Anda untuk mendokumentasikan materi yang telah disampaikan.
            </p>
        </div>
    </div>

    <form id="guruAgendaForm" action="{{ route('guru.agenda.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/40 overflow-hidden">
            <div class="p-8 md:p-12 space-y-10">
                <!-- Kelas & Mapel -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label for="class_id" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Pilih Kelas</label>
                        <div class="relative">
                            <select name="class_id" id="class_id" required class="appearance-none block w-full pl-4 pr-10 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-bold text-gray-900">
                                <option value="">Pilih Kelas...</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ (old('class_id') == $class->id || ($selected_schedule && $selected_schedule->class_id == $class->id)) ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('class_id')
                            <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <label for="subject_id" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Mata Pelajaran</label>
                        <div class="relative">
                            <select name="subject_id" id="subject_id" required class="appearance-none block w-full pl-4 pr-10 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-bold text-gray-900">
                                <option value="">Pilih Mapel...</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ (old('subject_id') == $subject->id || ($selected_schedule && $selected_schedule->subject_id == $subject->id)) ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('subject_id')
                            <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tanggal & Ruangan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label for="date" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tanggal Kegiatan</label>
                        <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required 
                               class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('date')
                            <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <label for="room" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Ruangan <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="room" id="room" required class="appearance-none block w-full pl-4 pr-10 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-bold text-gray-900 @error('room') border-rose-500 bg-rose-50 @enderror">
                                <option value="">Pilih Ruangan...</option>
                                @foreach($defaultRooms as $r)
                                    <option value="{{ $r }}" {{ old('room') == $r ? 'selected' : '' }}>{{ $r }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <p id="room-hint" class="text-[10px] font-bold text-blue-500 ml-1 hidden">
                            <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Menampilkan ruangan sesuai jadwal Anda
                        </p>
                        <p id="room-fallback-hint" class="text-[10px] font-bold text-amber-500 ml-1 hidden">
                            <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5C2.57 18.333 3.532 20 5.072 20z"></path></svg>
                            Tidak ada jadwal di hari ini. Menampilkan semua ruangan Anda.
                        </p>
                        @error('room')
                            <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Judul & Deskripsi -->
                <div class="space-y-8 pt-4">
                    <div class="space-y-3">
                        <label for="title" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Judul / Materi Pokok</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="Contoh: Pengenalan Dasar OOP PHP"
                               class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-900 placeholder:text-gray-300 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('title')
                            <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <label for="description" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Detail Aktivitas</label>
                        <textarea name="description" id="description" rows="6" required placeholder="Jelaskan apa saja yang dipelajari, progress materi, atau catatan khusus..."
                                  class="w-full bg-gray-50 border-transparent rounded-[2rem] px-8 py-6 text-sm font-medium text-gray-600 placeholder:text-gray-300 focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all leading-relaxed">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="px-8 py-8 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end gap-4">
                <button type="reset" class="px-8 py-4 text-sm font-bold text-gray-400 hover:text-rose-600 transition-all">Reset Formulir</button>
                <button type="submit" class="px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-bold shadow-xl shadow-blue-500/20 hover:scale-[1.02] transition-all duration-300">
                    Simpan Jurnal
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const GURU_API_URL = '{{ route("guru.agenda.get-schedule-info") }}';
    let subjectRoomMap = {};

    function buildGuruRoomOptions(rooms) {
        const sel = document.getElementById('room');
        const current = sel.value;
        sel.innerHTML = '<option value="">Pilih Ruangan...</option>';
        rooms.forEach(r => {
            const opt = document.createElement('option');
            opt.value = r; opt.textContent = r;
            if (r === current) opt.selected = true;
            sel.appendChild(opt);
        });
    }

    function buildGuruSubjectOptions(subjects) {
        const sel = document.getElementById('subject_id');
        const current = sel.value;
        subjectRoomMap = {};
        sel.innerHTML = '<option value="">Pilih Mapel...</option>';
        subjects.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id; opt.textContent = s.name;
            if (String(s.id) === String(current)) opt.selected = true;
            sel.appendChild(opt);
            subjectRoomMap[s.id] = s.room;
        });
    }

    function fetchGuruScheduleInfo() {
        const classId = document.getElementById('class_id').value;
        const date    = document.getElementById('date').value;
        if (!classId || !date) return;

        fetch(`${GURU_API_URL}?class_id=${classId}&date=${date}`)
            .then(r => r.json())
            .then(data => {
                buildGuruSubjectOptions(data.subjects);
                buildGuruRoomOptions(data.rooms);

                const hint     = document.getElementById('room-hint');
                const fallback = document.getElementById('room-fallback-hint');
                if (data.has_schedule) {
                    hint.classList.remove('hidden');
                    fallback.classList.add('hidden');
                } else {
                    hint.classList.add('hidden');
                    fallback.classList.remove('hidden');
                }
            }).catch(() => {});
    }

    document.getElementById('class_id').addEventListener('change', fetchGuruScheduleInfo);
    document.getElementById('date').addEventListener('change', fetchGuruScheduleInfo);

    // Auto-select room when subject is chosen
    document.getElementById('subject_id').addEventListener('change', function() {
        const room = subjectRoomMap[this.value];
        if (room) {
            const roomSel = document.getElementById('room');
            for (let opt of roomSel.options) {
                if (opt.value === room) { opt.selected = true; break; }
            }
        }
    });

    // Trigger on load if class pre-selected
    if (document.getElementById('class_id').value) {
        fetchGuruScheduleInfo();
    }
</script>
@endpush
@endsection
