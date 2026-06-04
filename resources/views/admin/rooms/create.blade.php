@extends('layouts.admin')

@section('title', 'Tambah Ruangan')
@section('header', 'Tambah Ruangan')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-900">Formulir Tambah Ruangan</h3>
            <p class="text-sm text-gray-500 mt-1">Isikan detail ruangan baru untuk sistem.</p>
        </div>
        
        <form action="{{ route('admin.rooms.store') }}" method="POST">
            @csrf
            <div class="p-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">Nama Ruangan</label>
                        <input type="text" name="name" class="w-full p-4 bg-gray-50 rounded-2xl border-transparent focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required placeholder="Contoh: Lab Komputer 1">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">Tipe Ruangan</label>
                        <select name="type" class="w-full p-4 bg-gray-50 rounded-2xl border-transparent focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                            <option value="">Pilih Tipe Ruangan</option>
                            <option value="Laboratorium">Laboratorium</option>
                            <option value="Kelas">Kelas</option>
                        </select>
                    </div>
                    <div class="space-y-2 col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700">Kapasitas (Siswa)</label>
                        <input type="number" name="capacity" class="w-full p-4 bg-gray-50 rounded-2xl border-transparent focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Contoh: 30">
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-4 p-8 bg-gray-50 border-t border-gray-100">
                <a href="{{ route('admin.rooms.index') }}" class="px-8 py-3 bg-white rounded-2xl text-sm font-bold text-gray-600 hover:bg-gray-100 border border-gray-200 transition">Batal</a>
                <button type="submit" class="px-8 py-3 bg-blue-600 rounded-2xl text-sm font-bold text-white hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition">Simpan Ruangan</button>
            </div>
        </form>
    </div>
</div>
@endsection
