{{-- resources/views/sekretaris/agenda/edit.blade.php --}}
@extends('layouts.sekretaris')

@section('title', 'Edit Agenda')
@section('header', 'Perbarui Agenda Kelas')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    <!-- Breadcrumbs & Header -->
    <div class="mb-8">
        <nav class="flex mb-4 text-xs font-bold uppercase tracking-widest text-gray-400">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('sekretaris.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a></li>
                <li><svg class="w-3 h-3 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li><a href="{{ route('sekretaris.agenda.index') }}" class="hover:text-blue-600 transition-colors">Agenda</a></li>
                <li><svg class="w-3 h-3 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                <li class="text-blue-600">Edit Agenda</li>
            </ol>
        </nav>
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Edit Agenda</h1>
        <p class="text-gray-500 mt-1 font-medium text-sm">Sesuaikan informasi agenda yang telah dicatat sebelumnya.</p>
    </div>

    <form id="agendaForm" action="{{ route('sekretaris.agenda.update', $agenda) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center bg-gray-50/30">
                <div class="w-2 h-6 bg-blue-600 rounded-full mr-3"></div>
                <h3 class="text-lg font-bold text-gray-900">Informasi Utama Agenda</h3>
            </div>
            
            <div class="p-8 space-y-6">
                <!-- Judul -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Judul Agenda / Materi Pembelajaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $agenda->title) }}" required
                           class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-bold placeholder-gray-300 @error('title') border-rose-500 bg-rose-50 @enderror"
                           placeholder="Contoh: Pembahasan Logaritma Bab 2">
                    @error('title')
                        <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Kelas (Locked) -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Kelas Anda</label>
                        <input type="text" value="{{ $agenda->class->name }}" disabled
                               class="w-full px-6 py-4 bg-gray-100 border-transparent rounded-2xl text-sm font-bold text-gray-500 cursor-not-allowed">
                    </div>

                    <!-- Tanggal -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tanggal Kegiatan <span class="text-rose-500">*</span></label>
                        <input type="date" name="date" id="date" value="{{ old('date', \Carbon\Carbon::parse($agenda->date)->format('Y-m-d')) }}" required
                               class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-bold @error('date') border-rose-500 bg-rose-50 @enderror">
                        @error('date')
                            <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Mata Pelajaran -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Mata Pelajaran</label>
                        <div class="relative">
                            <select name="subject_id" id="subject_id"
                                    class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-bold appearance-none">
                                <option value="">Pilih Mata Pelajaran (Opsional)</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" data-teacher="{{ $subject->teacher_id }}" {{ old('subject_id', $agenda->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Ruangan -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Ruangan <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="room" id="room" required
                                    class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-bold appearance-none @error('room') border-rose-500 bg-rose-50 @enderror">
                                <option value="">Pilih Ruangan</option>
                                @php $currentRoom = old('room', $agenda->room); @endphp
                                @foreach($scheduleRooms as $r)
                                    <option value="{{ $r }}" {{ $currentRoom == $r ? 'selected' : '' }}>{{ $r }}</option>
                                @endforeach
                                {{-- Fallback: add the existing agenda room if not in scheduled rooms --}}
                                @if($agenda->room && !$scheduleRooms->contains($agenda->room))
                                    <option value="{{ $agenda->room }}" selected>{{ $agenda->room }}</option>
                                @endif
                                @if($scheduleRooms->isEmpty())
                                    @foreach($allRooms as $r)
                                        <option value="{{ $r }}" {{ $currentRoom == $r ? 'selected' : '' }}>{{ $r }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <p id="room-hint" class="text-[10px] font-bold text-blue-500 ml-1 {{ $scheduleRooms->isNotEmpty() ? '' : 'hidden' }}">
                            <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Menampilkan ruangan sesuai jadwal hari yang dipilih
                        </p>
                        <p id="room-fallback-hint" class="text-[10px] font-bold text-amber-500 ml-1 {{ $scheduleRooms->isEmpty() ? '' : 'hidden' }}">
                            <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5C2.57 18.333 3.532 20 5.072 20z"></path></svg>
                            Tidak ada jadwal pada hari ini. Menampilkan semua ruangan.
                        </p>
                        @error('room')
                            <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Guru -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Guru Pengampu <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="teacher_id" id="teacher_id" required
                                    class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-bold appearance-none @error('teacher_id') border-rose-500 bg-rose-50 @enderror">
                                <option value="">Pilih Guru</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $agenda->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('teacher_id')
                            <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detail Deskripsi -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center bg-gray-50/30">
                <div class="w-2 h-6 bg-blue-600 rounded-full mr-3"></div>
                <h3 class="text-lg font-bold text-gray-900">Rincian Materi & Kegiatan</h3>
            </div>
            <div class="p-8 space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Deskripsi Kegiatan / Catatan Guru <span class="text-rose-500">*</span></label>
                    <div class="rounded-2xl border border-gray-100 overflow-hidden shadow-inner">
                        <div id="editor" style="min-height: 250px;" class="bg-white"></div>
                    </div>
                    <textarea name="description" id="description" class="hidden"></textarea>
                    @error('description')
                        <p class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Lampiran Dokumen (Kosongkan jika tidak ingin mengubah)</label>
                    <div class="relative group">
                        <input type="file" name="attachment" id="attachment" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf,.doc,.docx,.jpg,.png,.xlsx">
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl p-10 text-center group-hover:border-blue-500 group-hover:bg-blue-50 transition-all duration-300">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 group-hover:bg-white transition-all shadow-sm">
                                <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-900" id="fileNameText">
                                @if($agenda->attachments)
                                    📎 {{ basename($agenda->attachments) }}
                                @else
                                    Klik atau Seret File Baru ke Sini
                                @endif
                            </p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-2">PDF, DOC, JPG, PNG, XLSX (Max 5MB)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit & Status -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-6">
                <label class="flex items-center cursor-pointer group">
                    <input type="radio" name="status" value="published" {{ old('status', $agenda->status) == 'published' ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500/20">
                    <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors">Published</span>
                </label>
                <label class="flex items-center cursor-pointer group">
                    <input type="radio" name="status" value="draft" {{ old('status', $agenda->status) == 'draft' ? 'checked' : '' }}
                           class="w-5 h-5 text-gray-400 border-gray-300 focus:ring-gray-500/20">
                    <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-gray-900 transition-colors">Draft</span>
                </label>
            </div>
            
            <div class="flex items-center space-x-4 w-full md:w-auto">
                <a href="{{ route('sekretaris.agenda.index') }}" 
                   class="flex-1 md:flex-none px-10 py-4 bg-white border border-gray-200 text-gray-700 rounded-2xl font-bold text-sm hover:bg-gray-50 transition-all text-center">Batal</a>
                <button type="submit"
                        class="flex-1 md:flex-none px-12 py-4 text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl font-bold text-sm hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/20">
                    Update Agenda
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Tuliskan rincian kegiatan atau materi yang dibahas...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                ['link', 'blockquote', 'code-block'],
                ['clean']
            ]
        }
    });
    
    quill.root.innerHTML = {!! json_encode(old('description', $agenda->description)) !!};
    
    document.getElementById('agendaForm').onsubmit = function() {
        var content = quill.root.innerHTML;
        if (content === '<p><br></p>') content = '';
        document.querySelector('#description').value = content;
        return true;
    };

    // ── Dynamic Schedule Info via AJAX ─────────────────────────────────────
    const API_URL = '{{ route("sekretaris.agenda.get-schedule-info") }}';
    let subjectTeacherMap = {};
    const CURRENT_ROOM = '{{ old('room', $agenda->room) }}';

    function buildRoomOptions(rooms, selectedRoom) {
        const select = document.getElementById('room');
        const current = selectedRoom || select.value;
        select.innerHTML = '<option value="">Pilih Ruangan</option>';
        rooms.forEach(r => {
            const opt = document.createElement('option');
            opt.value = r;
            opt.textContent = r;
            if (r === current) opt.selected = true;
            select.appendChild(opt);
        });
        // If currentRoom not in list, add it
        if (current && !rooms.includes(current)) {
            const opt = document.createElement('option');
            opt.value = current;
            opt.textContent = current;
            opt.selected = true;
            select.appendChild(opt);
        }
    }

    function buildSubjectOptions(subjects, selectedSubjectId) {
        const select = document.getElementById('subject_id');
        const current = selectedSubjectId || select.value;
        subjectTeacherMap = {};
        select.innerHTML = '<option value="">Pilih Mata Pelajaran (Opsional)</option>';
        subjects.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.name;
            if (String(s.id) === String(current)) opt.selected = true;
            select.appendChild(opt);
            subjectTeacherMap[s.id] = { teacher_id: s.teacher_id, room: s.room };
        });
    }

    function fetchScheduleInfo(date) {
        if (!date) return;
        fetch(`${API_URL}?date=${date}`)
            .then(r => r.json())
            .then(data => {
                buildSubjectOptions(data.subjects, '{{ old('subject_id', $agenda->subject_id) }}');
                buildRoomOptions(data.rooms, CURRENT_ROOM);

                const hint = document.getElementById('room-hint');
                const fallback = document.getElementById('room-fallback-hint');
                if (data.has_schedule) {
                    hint.classList.remove('hidden');
                    fallback.classList.add('hidden');
                } else {
                    hint.classList.add('hidden');
                    fallback.classList.remove('hidden');
                }
            })
            .catch(() => {});
    }

    document.getElementById('date').addEventListener('change', function() {
        fetchScheduleInfo(this.value);
    });

    document.getElementById('subject_id').addEventListener('change', function() {
        const val = this.value;
        if (!val) return;
        const info = subjectTeacherMap[val];
        if (info) {
            if (info.teacher_id) {
                const teacherSel = document.getElementById('teacher_id');
                for (let opt of teacherSel.options) {
                    if (opt.value == info.teacher_id) { opt.selected = true; break; }
                }
            }
            if (info.room) {
                const roomSel = document.getElementById('room');
                for (let opt of roomSel.options) {
                    if (opt.value == info.room) { opt.selected = true; break; }
                }
            }
        }
    });

    document.getElementById('attachment').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('fileNameText').textContent = '📎 ' + file.name;
            document.getElementById('fileNameText').classList.add('text-blue-600');
        }
    });
</script>
@endpush
@endsection