{{-- resources/views/admin/subjects/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Mata Pelajaran')
@section('header', 'Edit Data Mata Pelajaran')

@section('content')
<div class="max-w-3xl mx-auto pb-12">
    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <nav class="flex text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.subjects.index') }}" class="hover:text-indigo-600 transition-colors">Mata Pelajaran</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium">Edit Mata Pelajaran</span>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Edit Data Mata Pelajaran</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.subjects.update', $subject) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-8 space-y-6">
                <!-- Informasi Mata Pelajaran -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Mapel -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Mata Pelajaran <span class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $subject->code) }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('code') border-red-500 @enderror">
                        @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Nama Mapel -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Mata Pelajaran <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $subject->name) }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Guru Pengampu -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Guru Pengampu <span class="text-red-500">*</span></label>
                        <select name="teacher_id" required
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm @error('teacher_id') border-red-500 @enderror">
                            <option value="">Pilih Guru</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $subject->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }} ({{ $teacher->nip ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Jam Pelajaran -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Pelajaran Per Minggu <span class="text-red-500">*</span></label>
                        <input type="number" name="credit_hours" value="{{ old('credit_hours', $subject->credit_hours) }}" min="1" max="40" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('credit_hours') border-red-500 @enderror"
                               placeholder="Contoh: 4">
                        @error('credit_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-[10px] text-gray-500 mt-1">1 JP = 45 menit. Masukkan total JP per minggu.</p>
                    </div>
                </div>
                
                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Mata Pelajaran</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm @error('description') border-red-500 @enderror">{{ old('description', $subject->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                @if($subject->created_at)
                <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-500 border border-gray-100 flex flex-col md:flex-row justify-between">
                    <div><span class="font-semibold text-gray-700">Terdaftar:</span> {{ $subject->created_at->format('d M Y, H:i') }}</div>
                    <div><span class="font-semibold text-gray-700">Diperbarui:</span> {{ $subject->updated_at->format('d M Y, H:i') }}</div>
                </div>
                @endif
            </div>
            
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.subjects.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-100 transition-all">Batal</a>
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection