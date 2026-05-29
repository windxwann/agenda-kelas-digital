{{-- resources/views/sekretaris/agenda/archive.blade.php --}}
@extends('layouts.sekretaris')

@section('title', 'Arsip Agenda Kelas')
@section('header', 'Arsip Agenda Kelas')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Arsip Agenda Kelas</h1>
            <p class="text-gray-500 mt-1 font-medium text-sm">Lihat kembali seluruh agenda kelas dari tahun-tahun sebelumnya.</p>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('sekretaris.agenda.archive') }}" class="flex flex-col lg:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari judul agenda..." 
                       class="block w-full pl-12 pr-4 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all text-sm font-medium">
            </div>

            <div class="relative w-full lg:w-48">
                <select name="class_id" onchange="this.form.submit()" 
                        class="appearance-none block w-full pl-4 pr-10 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all text-sm font-medium">
                    <option value="">Semua Kelas Saya</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="px-6 py-3.5 text-white bg-blue-600 hover:bg-blue-700 rounded-xl font-bold text-sm transition-all shadow-sm">Filter</button>
        </form>
    </div>
    
    <!-- Agendas Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <h3 class="text-lg font-bold text-gray-900">Daftar Arsip Agenda</h3>
            <span class="px-4 py-1.5 bg-white text-[10px] font-bold text-gray-400 uppercase tracking-widest rounded-xl border border-gray-100">{{ $agendas->total() }} Agenda Ditemukan</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50/30">
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kelas</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Judul</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Guru</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($agendas as $agenda)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-4 text-sm font-bold text-gray-900">{{ $agenda->date->translatedFormat('d M Y') }}</td>
                        <td class="px-8 py-4">
                            <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 rounded-md border border-blue-100">{{ $agenda->class->name }}</span>
                        </td>
                        <td class="px-8 py-4 text-sm font-medium text-gray-700">{{ $agenda->title }}</td>
                        <td class="px-8 py-4 text-sm font-medium text-gray-500">{{ $agenda->teacher->name }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center text-gray-500 italic">Tidak ada agenda dalam arsip.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($agendas->hasPages())
        <div class="px-8 py-6 border-t border-gray-50 bg-gray-50/30">
            {{ $agendas->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
