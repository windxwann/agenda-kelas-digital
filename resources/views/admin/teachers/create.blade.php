{{-- resources/views/admin/teachers/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Guru')
@section('header', 'Tambah Guru Baru')

@section('content')
<div class="max-w-3xl mx-auto pb-12">
    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <nav class="flex text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.teachers.index') }}" class="hover:text-indigo-600 transition-colors">Guru</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium">Tambah Guru</span>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Guru Baru</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.teachers.store') }}" method="POST">
            @csrf
            
            <div class="p-8 space-y-6">
                <!-- Informasi Pribadi & Kontak -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('name') border-red-500 @enderror"
                               placeholder="Contoh: Drs. Ahmad Budiman, M.Pd">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- NIP -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIP (Nomor Induk Pegawai) <span class="text-red-500">*</span></label>
                        <input type="text" name="nip" value="{{ old('nip') }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('nip') border-red-500 @enderror"
                               placeholder="Contoh: 198001012010011001">
                        @error('nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-[10px] text-gray-500 mt-1">NIP harus unik dan tidak boleh sama dengan guru lain.</p>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('email') border-red-500 @enderror"
                               placeholder="contoh: guru@email.com">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm @error('phone') border-red-500 @enderror"
                               placeholder="Contoh: 08123456789">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                    <textarea name="address" rows="3"
                              class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm @error('address') border-red-500 @enderror"
                              placeholder="Alamat lengkap guru...">{{ old('address') }}</textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="text" name="password" value="{{ old('password', 'password') }}" required
                           class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all text-sm @error('password') border-red-500 @enderror">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-[10px] text-gray-500 mt-1">Password default: <strong>password</strong></p>
                </div>
            </div>
            
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.teachers.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-100 transition-all">Batal</a>
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-sm">Simpan Guru</button>
            </div>
        </form>
    </div>
</div>
@endsection