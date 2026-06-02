{{-- resources/views/wakasek/teaching/index.blade.php --}}
@extends('layouts.wakasek')

@section('title', 'Kinerja Pengajaran Guru')
@section('header', 'Kinerja Guru')

@section('content')
<div class="space-y-6 pb-8">
    <!-- Header Section -->
    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-48 h-48 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Kinerja Pengajaran Guru</h1>
                <p class="mt-2 text-xs sm:text-sm text-gray-500 font-medium max-w-2xl">
                    Pantau keaktifan tenaga pendidik dalam mendokumentasikan proses belajar mengajar secara real-time.
                </p>
            </div>
            
            <div class="w-full md:w-auto">
                <form method="GET" action="{{ route('wakasek-kurikulum.teaching.index') }}" class="relative group">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIP..."
                           class="w-full md:w-64 pl-10 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-xs sm:text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-inner">
                    <div class="absolute left-3.5 top-3.5 text-gray-400 group-hover:text-blue-600 transition-colors pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Stats Summary for Monitoring -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex flex-col items-center justify-center text-center">
            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Guru</span>
            <span class="text-xl sm:text-2xl font-black text-gray-900">{{ $teachers->total() }}</span>
        </div>
        <div class="bg-blue-50/50 p-5 rounded-3xl border border-blue-100 flex flex-col items-center justify-center text-center">
            <span class="text-[8px] font-black text-blue-500 uppercase tracking-widest mb-1">Total Agenda</span>
            <span class="text-xl sm:text-2xl font-black text-blue-700">{{ $teachers->sum('agendas_count') }}</span>
        </div>
        <div class="bg-emerald-50/50 p-5 rounded-3xl border border-emerald-100 flex flex-col items-center justify-center text-center">
            <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest mb-1">Rata-rata</span>
            <span class="text-xl sm:text-2xl font-black text-emerald-700">{{ $teachers->count() > 0 ? round($teachers->sum('agendas_count') / $teachers->count(), 1) : 0 }}</span>
        </div>
        <div class="bg-gray-900 p-5 rounded-3xl shadow-lg flex flex-col items-center justify-center text-center">
            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Status</span>
            <span class="text-xs font-black text-white uppercase tracking-widest">AKTIF</span>
        </div>
    </div>

    <!-- Content Display -->
    <div class="space-y-4">
        {{-- Desktop View (Table) --}}
        <div class="hidden lg:block bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Identitas Guru</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kontak</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Keaktifan</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @forelse($teachers as $teacher)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-blue-600 text-white rounded-xl flex items-center justify-center text-xs font-black shadow-lg shadow-blue-200 group-hover:scale-110 transition-transform">
                                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="text-sm font-black text-gray-900 group-hover:text-blue-600 transition-colors truncate">{{ $teacher->name }}</span>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">NIP: {{ $teacher->nip ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-gray-600">{{ $teacher->email }}</span>
                                        <span class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $teacher->phone ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-sm font-black text-emerald-600">{{ $teacher->agendas_count }}</span>
                                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">Jurnal Terbit</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('wakasek-kurikulum.teaching.show', $teacher->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center text-gray-400 font-medium italic">Data guru tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile View (Cards) --}}
        <div class="lg:hidden space-y-4">
            @forelse($teachers as $teacher)
                <div class="bg-white p-5 rounded-[2rem] border border-gray-100 shadow-sm space-y-4 group active:scale-[0.98] transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 text-white rounded-2xl flex items-center justify-center text-xs font-black shadow-lg">
                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-gray-900 leading-tight">{{ $teacher->name }}</span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">NIP: {{ $teacher->nip ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-100 text-center">
                            <span class="block text-xs font-black text-emerald-600 leading-none">{{ $teacher->agendas_count }}</span>
                            <span class="text-[7px] font-black text-emerald-500/60 uppercase tracking-tighter">Jurnal</span>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-50 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-[9px] text-gray-400 font-bold truncate uppercase tracking-widest">{{ $teacher->email }}</p>
                        </div>
                        <a href="{{ route('wakasek-kurikulum.teaching.show', $teacher->id) }}" class="flex-shrink-0 px-5 py-2.5 bg-gray-900 text-white rounded-xl text-[9px] font-black uppercase tracking-[0.2em] shadow-lg shadow-gray-200">
                            Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white p-12 rounded-[2rem] border border-gray-100 text-center">
                    <p class="text-sm text-gray-400 italic">Data guru tidak ditemukan.</p>
                </div>
            @endforelse
        </div>
        
        @if($teachers->hasPages())
        <div class="pt-6">
            {{ $teachers->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
