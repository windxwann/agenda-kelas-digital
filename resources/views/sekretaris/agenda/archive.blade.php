{{-- resources/views/sekretaris/agenda/archive.blade.php --}}
@extends('layouts.sekretaris')

@section('title', 'Arsip Agenda Kelas')
@section('header', 'Arsip Agenda Kelas')

@section('content')
<div class="space-y-6 sm:space-y-8 pb-8">
    <!-- Header Section -->
    <div class="bg-white rounded-[2.5rem] p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-48 h-48 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Arsip Agenda Kelas</h1>
                <p class="mt-2 text-xs sm:text-sm text-gray-500 font-medium max-w-2xl">
                    Jelajahi kembali catatan sejarah kegiatan belajar mengajar kelas Anda.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-4 sm:p-6">
        <form method="GET" action="{{ route('sekretaris.agenda.archive') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="relative lg:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul agenda..." 
                       class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-xs sm:text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-inner">
            </div>

            <select name="class_id" onchange="this.form.submit()" 
                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-500/10 transition-all text-xs sm:text-sm font-bold text-gray-700 shadow-inner">
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:scale-[1.02] transition-all">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'class_id']))
                    <a href="{{ route('sekretaris.agenda.archive') }}" class="p-3 text-rose-500 bg-rose-50 rounded-2xl hover:bg-rose-100 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Agendas Display -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <h3 class="text-sm sm:text-lg font-black text-gray-900">Daftar Arsip Agenda</h3>
            <span class="px-3 py-1.5 bg-white text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] rounded-xl border border-gray-100 shadow-sm">{{ $agendas->total() }} Agenda</span>
        </div>
        
        {{-- Desktop View --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-50">
                <thead>
                    <tr class="bg-gray-50/20">
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kelas</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Judul</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Guru</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($agendas as $agenda)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-5 text-sm font-black text-gray-900">{{ $agenda->date->translatedFormat('d M Y') }}</td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 text-[10px] font-black bg-blue-50 text-blue-700 rounded-lg border border-blue-100 uppercase tracking-widest">{{ $agenda->class->name }}</span>
                        </td>
                        <td class="px-8 py-5 text-sm font-bold text-gray-700">{{ $agenda->title }}</td>
                        <td class="px-8 py-5 text-sm font-medium text-gray-500">{{ $agenda->teacher->name }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-8 py-20 text-center text-gray-400 font-medium italic">Tidak ada agenda dalam arsip.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile View (Cards) --}}
        <div class="lg:hidden divide-y divide-gray-50">
            @forelse($agendas as $agenda)
            <div class="p-5 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-600 text-white rounded-2xl flex flex-col items-center justify-center shadow-lg shadow-indigo-200 flex-shrink-0">
                        <span class="text-[8px] font-black uppercase leading-none opacity-60 mb-0.5">{{ $agenda->date->translatedFormat('M') }}</span>
                        <span class="text-sm font-black leading-none">{{ $agenda->date->format('d') }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sm font-black text-gray-900 leading-tight">{{ $agenda->title }}</h4>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $agenda->class->name }} • {{ $agenda->teacher->name }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-10 text-center"><p class="text-[9px] font-black text-gray-300 uppercase tracking-widest italic">Tidak ada agenda.</p></div>
            @endforelse
        </div>
        
        @if($agendas->hasPages())
        <div class="px-6 sm:px-8 py-5 sm:py-6 border-t border-gray-50 bg-gray-50/30">
            {{ $agendas->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
