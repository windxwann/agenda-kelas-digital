{{-- resources/views/sekretaris/agenda/index.blade.php --}}
@extends('layouts.sekretaris')

@section('title', 'Agenda Kelas')
@section('header', 'Agenda Kelas')

@section('content')
<div class="space-y-6 sm:space-y-8 pb-8">
    <!-- Header Section -->
    <div class="bg-white rounded-[2.5rem] p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-48 h-48 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Agenda Kelas</h1>
                <p class="mt-2 text-xs sm:text-sm text-gray-500 font-medium max-w-2xl">
                    Kelola daftar agenda harian, ringkasan materi, dan tugas kelas Anda.
                </p>
            </div>
            <a href="{{ route('sekretaris.agenda.create') }}" 
               class="inline-flex items-center justify-center px-6 py-3 text-xs sm:text-sm font-black text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg shadow-blue-500/20 hover:from-blue-700 hover:to-indigo-700 transition-all uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Agenda
            </a>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-4 sm:p-6">
        <form method="GET" action="{{ route('sekretaris.agenda.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="relative lg:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul agenda..." 
                       class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-xs sm:text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-inner">
            </div>

            <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                   class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-500/10 transition-all text-xs sm:text-sm font-bold shadow-inner">

            <div class="flex gap-2">
                <select name="status" onchange="this.form.submit()" 
                        class="flex-1 px-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-500/10 transition-all text-xs sm:text-sm font-bold text-gray-700 shadow-inner">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                @if(request()->anyFilled(['search', 'date', 'status']))
                    <a href="{{ route('sekretaris.agenda.index') }}" class="p-3 text-rose-500 bg-rose-50 rounded-2xl hover:bg-rose-100 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Agendas Display -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <h3 class="text-sm sm:text-lg font-black text-gray-900">Daftar Agenda</h3>
            <span class="px-3 py-1.5 bg-white text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] rounded-xl border border-gray-100 shadow-sm">{{ $agendas->total() }} Agenda</span>
        </div>
        
        {{-- Desktop View --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-50">
                <thead>
                    <tr class="bg-gray-50/20">
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Waktu & Kelas</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mapel & Guru</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Materi</th>
                        <th class="px-8 py-5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Opsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($agendas as $agenda)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="text-sm font-black text-gray-900">{{ $agenda->date->translatedFormat('d M Y') }}</div>
                            <div class="mt-1 text-[10px] font-bold text-blue-600 uppercase tracking-widest">{{ $agenda->class->name }}</div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-black text-gray-900">{{ $agenda->subject->name ?? '-' }}</div>
                            <div class="text-[10px] font-medium text-gray-400 mt-0.5">{{ $agenda->teacher->name }}</div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-bold text-gray-700 truncate max-w-xs">{{ $agenda->title }}</div>
                            <div class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $agenda->room ?? '-' }}</div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1 text-[9px] font-black {{ $agenda->status == 'published' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100' }} rounded-lg border uppercase tracking-widest">
                                {{ $agenda->status }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick="previewAgenda({{ $agenda->id }})" class="p-2 text-gray-400 hover:text-blue-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></button>
                                <a href="{{ route('sekretaris.agenda.edit', $agenda) }}" class="p-2 text-gray-400 hover:text-green-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-8 py-20 text-center text-gray-400 font-medium italic">Tidak ada agenda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile View (Cards) --}}
        <div class="lg:hidden divide-y divide-gray-50">
            @forelse($agendas as $agenda)
            <div class="p-5 space-y-4 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-600 text-white rounded-2xl flex flex-col items-center justify-center shadow-lg shadow-indigo-200">
                            <span class="text-[8px] font-black uppercase leading-none opacity-60 mb-0.5">{{ $agenda->date->translatedFormat('M') }}</span>
                            <span class="text-sm font-black leading-none">{{ $agenda->date->format('d') }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-gray-900 leading-tight">{{ $agenda->title }}</span>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $agenda->subject->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] px-1">
                    <span>{{ $agenda->class->name }}</span>
                    <a href="{{ route('sekretaris.agenda.edit', $agenda) }}" class="text-blue-600">EDIT</a>
                </div>
            </div>
            @empty
            <div class="p-10 text-center text-gray-400 italic">Tidak ada agenda.</div>
            @endforelse
        </div>
        
        @if($agendas->hasPages())
        <div class="px-6 sm:px-8 py-5 sm:py-6 border-t border-gray-50 bg-gray-50/30">
            {{ $agendas->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm hidden items-end sm:items-center justify-center z-50">
    <div class="bg-white rounded-t-[2.5rem] sm:rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden border border-white/20">
        <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base sm:text-lg font-black text-gray-900">Preview Agenda</h3>
            <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div id="previewContent" class="px-6 sm:px-8 py-6 max-h-[60vh] overflow-y-auto">
            <!-- Isi modal dimuat via JS -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewAgenda(id) {
        const modal = document.getElementById('previewModal');
        const content = document.getElementById('previewContent');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        fetch(`{{ url('sekretaris/agenda') }}/${id}/preview`)
            .then(res => res.json())
            .then(data => {
                content.innerHTML = `
                    <div class="space-y-4">
                        <h4 class="text-lg font-black text-gray-900">${data.title}</h4>
                        <p class="text-sm font-bold text-gray-600">${data.date} | ${data.class_name}</p>
                        <div class="p-4 bg-gray-50 rounded-2xl text-xs text-gray-600 leading-relaxed">${data.description}</div>
                    </div>
                `;
            });
    }
    
    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endpush
@endsection
