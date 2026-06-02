{{-- resources/views/wakasek/teaching/show.blade.php --}}
@extends('layouts.wakasek')

@section('title', 'Detail Jurnal Mengajar - ' . $teacher->name)
@section('header', 'Detail Jurnal Mengajar')

@section('content')
<div class="space-y-6 pb-8">
    <!-- Header Section -->
    <div class="bg-white rounded-[2.5rem] p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-48 h-48 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-4 mb-3">
                    <a href="{{ route('wakasek-kurikulum.teaching.index') }}" class="w-10 h-10 bg-gray-50 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight truncate">{{ $teacher->name }}</h1>
                </div>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 ml-14">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">NIP: {{ $teacher->nip ?? '-' }}</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email: {{ $teacher->email }}</span>
                </div>
            </div>
            <div class="flex items-center ml-14 md:ml-0">
                <div class="px-5 py-2.5 bg-blue-50 text-blue-600 rounded-2xl flex items-center gap-3 border border-blue-100 shadow-sm">
                    <div class="text-center">
                        <div class="text-[8px] font-black uppercase tracking-widest opacity-60">Total Jurnal</div>
                        <div class="text-lg font-black leading-none mt-1">{{ $agendas->total() }}</div>
                    </div>
                </div>
            </div>
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
                            <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest w-48">Waktu</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest w-48">Kelas & Mapel</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest min-w-[300px]">Detail Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @forelse($agendas as $agenda)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6 align-top">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-gray-900 group-hover:text-blue-600 transition-colors">
                                            {{ \Carbon\Carbon::parse($agenda->date)->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter mt-1">
                                            {{ \Carbon\Carbon::parse($agenda->date)->translatedFormat('l') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 align-top">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-gray-900">{{ $agenda->class->name ?? '-' }}</span>
                                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-1">{{ $agenda->subject->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="space-y-3">
                                        <p class="text-sm text-gray-800 font-bold leading-relaxed">{{ $agenda->title }}</p>
                                        @if($agenda->description)
                                        <div class="text-xs text-gray-500 leading-relaxed italic prose prose-sm max-w-none">{!! $agenda->description !!}</div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-20 text-center text-gray-400 font-medium italic">Belum ada catatan mengajar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile View (Cards) --}}
        <div class="lg:hidden space-y-4">
            @forelse($agendas as $agenda)
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm space-y-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-600 text-white rounded-2xl flex flex-col items-center justify-center shadow-lg shadow-indigo-200">
                                <span class="text-[8px] font-black uppercase leading-none opacity-60 mb-0.5">{{ \Carbon\Carbon::parse($agenda->date)->translatedFormat('M') }}</span>
                                <span class="text-sm font-black leading-none">{{ \Carbon\Carbon::parse($agenda->date)->format('d') }}</span>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-sm font-black text-gray-900 truncate">{{ $agenda->class->name }}</span>
                                <span class="text-[9px] font-bold text-blue-600 uppercase tracking-widest truncate">{{ $agenda->subject->name }}</span>
                            </div>
                        </div>
                        @if($agenda->room)
                        <span class="px-2.5 py-1 bg-gray-50 text-gray-400 text-[8px] font-black uppercase tracking-widest rounded-lg border border-gray-100">
                            {{ $agenda->room }}
                        </span>
                        @endif
                    </div>
                    
                    <div class="bg-gray-50/50 rounded-2xl p-4 space-y-2">
                        <p class="text-sm font-black text-gray-800 leading-tight">{{ $agenda->title }}</p>
                        @if($agenda->description)
                        <div class="text-xs text-gray-500 leading-relaxed line-clamp-3 italic">{!! strip_tags($agenda->description) !!}</div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] px-1">
                        <span>{{ \Carbon\Carbon::parse($agenda->date)->translatedFormat('l') }}</span>
                        <span>TERBIT</span>
                    </div>
                </div>
            @empty
                <div class="bg-white p-12 rounded-[2rem] border border-gray-100 text-center">
                    <p class="text-sm text-gray-400 italic">Belum ada catatan mengajar.</p>
                </div>
            @endforelse
        </div>
        
        @if($agendas->hasPages())
        <div class="pt-6">
            {{ $agendas->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
