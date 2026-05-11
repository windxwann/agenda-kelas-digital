{{-- resources/views/admin/classes/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Kelas')
@section('header', 'Tambah Kelas Baru')

@section('content')
<div class="max-w-3xl mx-auto pb-12">
    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <nav class="flex text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.classes.index') }}" class="hover:text-indigo-600 transition-colors">Kelas</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium">Tambah Kelas</span>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Kelas Baru</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.classes.store') }}" method="POST">
            @csrf
            
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Kelas -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kelas <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('name') border-red-500 @enderror"
                               placeholder="Contoh: XII IPA 1">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Tingkat Kelas -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tingkat Kelas <span class="text-red-500">*</span></label>
                        <select name="grade_level" required
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                            <option value="">Pilih Tingkat</option>
                            <option value="X" {{ old('grade_level') == 'X' ? 'selected' : '' }}>Kelas X</option>
                            <option value="XI" {{ old('grade_level') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                            <option value="XII" {{ old('grade_level') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                        </select>
                        @error('grade_level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Wali Kelas -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Wali Kelas</label>
                    <select name="homeroom_teacher_id"
                            class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                        <option value="">Pilih Wali Kelas</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('homeroom_teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kapasitas -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kapasitas <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" value="{{ old('capacity', 30) }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-bold"
                               min="1" max="100">
                    </div>

                    <!-- Tahun Ajaran -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <input type="text" name="academic_year" value="{{ old('academic_year', date('Y') . '/' . (date('Y')+1)) }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm"
                               placeholder="Contoh: 2024/2025">
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm"
                              placeholder="Opsional...">{{ old('description') }}</textarea>
                </div>

                <!-- Status -->
                <div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                        <span class="ml-3 text-sm font-semibold text-gray-600">Aktifkan Kelas Sekarang</span>
                    </label>
                </div>
            </div>
            
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.classes.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-100 transition-all">Batal</a>
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-sm">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>
@endsection