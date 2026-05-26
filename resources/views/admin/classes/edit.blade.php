{{-- resources/views/admin/classes/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Kelas')
@section('header', 'Edit Kelas')

@section('content')
<div class="max-w-3xl mx-auto pb-12">
    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <nav class="flex text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.classes.index') }}" class="hover:text-indigo-600 transition-colors">Kelas</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium">Edit Kelas</span>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Edit Detail Kelas</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.classes.update', $class) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Kelas -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kelas <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $class->name) }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('name') border-red-500 @enderror"
                               placeholder="Contoh: XII IPA 1">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Tingkat Kelas -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tingkat Kelas <span class="text-red-500">*</span></label>
                        <select name="grade_level" required
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm">
                            <option value="X" {{ old('grade_level', $class->grade_level) == 'X' ? 'selected' : '' }}>Kelas X</option>
                            <option value="XI" {{ old('grade_level', $class->grade_level) == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                            <option value="XII" {{ old('grade_level', $class->grade_level) == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                        </select>
                    </div>
                </div>

                <!-- Wali Kelas -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Wali Kelas</label>
                    <select name="homeroom_teacher_id"
                            class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm">
                        <option value="">Pilih Wali Kelas</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('homeroom_teacher_id', $class->homeroom_teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Kapasitas Section -->
                <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                    <div class="flex flex-col md:flex-row justify-between gap-6">
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kapasitas Maksimal <span class="text-red-500">*</span></label>
                            <input type="number" name="capacity" id="capacity-input" value="{{ old('capacity', $class->capacity) }}" required
                                   class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-bold"
                                   min="1" max="100">
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between text-xs font-bold text-gray-500 mb-2">
                                <span>Keterisian: <span id="current-students">{{ $class->students_count ?? 0 }}</span></span>
                                <span id="capacity-percentage">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="capacity-progress" class="bg-indigo-600 h-full rounded-full transition-all duration-500" style="width: 0%"></div>
                            </div>
                            <p class="mt-2 text-[10px] text-gray-400 font-medium">Sisa: <span id="remaining-seats">0</span> Kursi Tersedia</p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tahun Ajaran -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <input type="text" name="academic_year" value="{{ old('academic_year', $class->academic_year) }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm"
                               placeholder="2024/2025">
                    </div>
                    


                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <div class="flex items-center py-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $class->is_active) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                                <span class="ml-3 text-sm text-gray-600 font-medium">Aktifkan Kelas</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm"
                              placeholder="Opsional...">{{ old('description', $class->description) }}</textarea>
                </div>
            </div>
            
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.classes.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-100 transition-all">Batal</a>
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('capacity-input');
        const currentCount = parseInt(document.getElementById('current-students').textContent);
        const progress = document.getElementById('capacity-progress');
        const percentageText = document.getElementById('capacity-percentage');
        const remainingText = document.getElementById('remaining-seats');

        function updatePreview() {
            const capacity = Math.max(parseInt(input.value) || 1, 1);
            const percentage = Math.min(Math.round((currentCount / capacity) * 100), 100);
            const remaining = Math.max(capacity - currentCount, 0);

            progress.style.width = percentage + '%';
            percentageText.textContent = percentage + '%';
            remainingText.textContent = remaining;

            if (percentage >= 100) { progress.classList.replace('bg-indigo-600', 'bg-red-500'); }
            else { progress.classList.add('bg-indigo-600'); progress.classList.remove('bg-red-500'); }
        }
        input.addEventListener('input', updatePreview);
        updatePreview();
    });
</script>
@endpush
@endsection