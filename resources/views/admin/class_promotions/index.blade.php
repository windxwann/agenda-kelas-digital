@extends('layouts.admin')

@section('title', 'Kenaikan Kelas')
@section('header', 'Kenaikan Kelas')

@section('content')
<div class="space-y-8 pb-8" x-data="promotionApp({{ Js::from($classes) }})">

    @if(!$activeYear)
    <div class="bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-2xl flex items-start gap-3">
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <p class="font-semibold">Belum Ada Tahun Ajaran Aktif</p>
            <p class="text-sm mt-1">Silakan aktifkan Tahun Ajaran terlebih dahulu di halaman <a href="{{ route('admin.academic-years.index') }}" class="underline font-medium">Manajemen Tahun Ajaran</a>.</p>
        </div>
    </div>
    @else
    <!-- Academic Year Status Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Tahun Ajaran Aktif</p>
            <h2 class="text-2xl font-bold text-gray-900 mt-1">{{ $activeYear->name }}</h2>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl font-semibold text-sm">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            Status: Aktif
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl flex items-start gap-3">
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-2xl flex items-start gap-3">
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <p class="font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Kenaikan Kelas</h1>
            <p class="mt-2 text-base text-gray-500 max-w-2xl">
                Memindahkan siswa dari kelas lama ke kelas baru secara massal untuk tahun ajaran berikutnya.
            </p>
        </div>
    </div>

    <!-- Promotion Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Step 1 & 2: Selection -->
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            <h3 class="font-bold text-gray-900 text-base border-b border-gray-100 pb-3">Pengaturan Kenaikan</h3>
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">1. Kelas Asal</label>
                <select x-model="sourceClassId" @change="loadStudents()" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">2. Kelas Tujuan</label>
                <select x-model="targetClassId" :disabled="isXIISelected" 
                        :class="isXIISelected ? 'opacity-50 cursor-not-allowed bg-gray-200' : ''"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    <option value="">-- Pilih Kelas Tujuan --</option>
                    <template x-for="cls in filteredTargetClasses" :key="cls.id">
                        <option :value="cls.id" x-text="cls.name"></option>
                    </template>
                </select>
                
                <p x-show="sourceClassId && filteredTargetClasses.length === 0 && !isXIISelected" class="text-[10px] text-amber-600 font-bold mt-2">
                    Tidak ada kelas dengan tingkat yang sama atau lebih tinggi.
                </p>

                <div x-show="isXIISelected" class="mt-4 p-4 bg-rose-50 border border-rose-100 rounded-xl">
                    <p class="text-xs font-bold text-rose-700">⚠️ Kelas XII Tidak Bisa Naik Kelas</p>
                    <p class="text-[10px] text-rose-600 mt-1">Siswa kelas XII sudah berada di tingkat akhir. Untuk memproses siswa yang lulus, silakan gunakan menu kelulusan.</p>
                    <a href="{{ route('admin.students.bulk-graduation') }}" class="inline-block mt-3 px-4 py-2 bg-rose-600 text-white text-[10px] font-bold rounded-lg hover:bg-rose-700 transition-colors">
                        Ke Menu Kelulusan →
                    </a>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">4. Wali Kelas Baru (Opsional)</label>
                <select x-model="newHomeroomId" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    <option value="">-- Tetap Wali Kelas Lama --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">3. Tahun Ajaran Tujuan</label>
                <select x-model="targetYearId" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" @if(!$year->is_active) selected @endif>{{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Step 3: Student Selection -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <h3 class="font-bold text-gray-900 text-base">Daftar Siswa Kelas Asal</h3>
                <div x-show="students.length > 0" class="flex items-center gap-3">
                    <button @click="selectAll()" type="button" class="text-xs text-blue-600 font-semibold hover:underline">Pilih Semua</button>
                    <button @click="deselectAll()" type="button" class="text-xs text-gray-500 font-semibold hover:underline">Batal Semua</button>
                </div>
            </div>

            <!-- Empty state -->
            <div x-show="!sourceClassId" class="py-16 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <p class="text-gray-500 font-medium">Pilih kelas asal untuk melihat daftarnya</p>
            </div>

            <!-- Loading -->
            <div x-show="loading" class="py-16 flex items-center justify-center">
                <div class="w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
            </div>

            <!-- No students -->
            <div x-show="sourceClassId && !loading && students.length === 0" class="py-16 flex flex-col items-center justify-center text-center">
                <p class="text-gray-400 font-medium">Tidak ada siswa di kelas ini.</p>
            </div>

            <!-- Student List -->
            <div x-show="students.length > 0 && !loading" class="space-y-2 max-h-72 overflow-y-auto pr-1">
                <template x-for="student in students" :key="student.id">
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/50 cursor-pointer transition-all" 
                           :class="selectedIds.includes(student.id) ? 'border-blue-300 bg-blue-50' : ''">
                        <input type="checkbox" :value="student.id" x-model="selectedIds" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                             x-text="student.name.substring(0, 2).toUpperCase()"></div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800" x-text="student.name"></p>
                            <p class="text-xs text-gray-400" x-text="'NIS: ' + (student.nis ?? '-')"></p>
                        </div>
                    </label>
                </template>
            </div>

            <!-- Action Button -->
            <div x-show="selectedIds.length > 0" class="mt-5 pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        <span class="font-bold text-blue-600" x-text="selectedIds.length"></span> siswa dipilih
                    </p>
                    <button @click="submitPromotion()" type="button"
                            :disabled="!targetClassId || !targetYearId"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                        Naikkan Kelas →
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for submission -->
    <form id="promotion-form" action="{{ route('admin.class-promotions.promote') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="source_class_id" x-bind:value="sourceClassId">
        <input type="hidden" name="target_class_id" x-bind:value="targetClassId">
        <input type="hidden" name="target_year_id" x-bind:value="targetYearId">
        <template x-for="id in selectedIds">
            <input type="hidden" name="student_ids[]" :value="id">
        </template>
    </form>

</div>
@endsection

@push('scripts')
<script>
function promotionApp(classes) {
    return {
        classes: classes,
        sourceClassId: '',
        targetClassId: '',
        newHomeroomId: '',
        targetYearId: '',
        students: [],
        selectedIds: [],
        loading: false,

        get filteredTargetClasses() {
            if (!this.sourceClassId) return this.classes;
            
            const sourceClass = this.classes.find(c => c.id == this.sourceClassId);
            if (!sourceClass) return this.classes;

            const gradeMapping = { 'X': 10, 'XI': 11, 'XII': 12 };
            const sourceLevel = gradeMapping[sourceClass.grade_level] || 0;

            return this.classes.filter(c => {
                const targetLevel = gradeMapping[c.grade_level] || 0;
                // Allow same grade level (repeating/moving) or higher
                return targetLevel >= sourceLevel && c.id != this.sourceClassId;
            });
        },

        get isXIISelected() {
            if (!this.sourceClassId) return false;
            const sourceClass = this.classes.find(c => c.id == this.sourceClassId);
            return sourceClass && sourceClass.grade_level === 'XII';
        },

        async loadStudents() {
            if (!this.sourceClassId) { this.students = []; return; }
            this.loading = true;
            this.students = [];
            this.selectedIds = [];
            this.targetClassId = ''; // Reset target class when source changes
            try {
                const res = await fetch(`/admin/class-promotions/preview?class_id=${this.sourceClassId}`);
                const data = await res.json();
                this.students = data.students;
            } catch(e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        selectAll() {
            this.selectedIds = this.students.map(s => s.id);
        },

        deselectAll() {
            this.selectedIds = [];
        },

        submitPromotion() {
            if (!this.targetClassId || !this.targetYearId) {
                alert('Harap pilih kelas tujuan dan tahun ajaran tujuan terlebih dahulu!');
                return;
            }
            if (!confirm(`Yakin ingin memindahkan ${this.selectedIds.length} siswa ke kelas baru? Tindakan ini tidak bisa dibatalkan.`)) return;

            // Populate and submit the hidden form
            const form = document.getElementById('promotion-form');
            
            // Remove old dynamic inputs
            form.querySelectorAll('input[name="source_class_id"], input[name="target_class_id"], input[name="new_homeroom_id"], input[name="target_year_id"], input[name="student_ids[]"]').forEach(el => el.remove());
            
            const addInput = (name, value) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                form.appendChild(input);
            };

            addInput('source_class_id', this.sourceClassId);
            addInput('target_class_id', this.targetClassId);
            addInput('new_homeroom_id', this.newHomeroomId);
            addInput('target_year_id', this.targetYearId);
            this.selectedIds.forEach(id => addInput('student_ids[]', id));
            
            form.submit();
        }
    };
}
</script>
@endpush
