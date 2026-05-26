{{-- resources/views/sekretaris/agenda/index.blade.php --}}
@extends('layouts.sekretaris')

@section('title', 'Agenda Kelas')
@section('header', 'Agenda Kelas')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Agenda Kelas</h1>
            <p class="text-gray-500 mt-1 font-medium text-sm">Kelola daftar agenda harian, ringkasan materi, dan tugas kelas.</p>
        </div>
        <a href="{{ route('sekretaris.agenda.create') }}" 
           class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg shadow-blue-500/20 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Agenda
        </a>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('sekretaris.agenda.index') }}" class="flex flex-col lg:flex-row gap-4">
            <!-- Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari judul agenda atau materi..." 
                       class="block w-full pl-12 pr-4 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-medium">
            </div>

            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3">
                <!-- Filter Tanggal -->
                <div class="relative w-full sm:w-48">
                    <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                           class="appearance-none block w-full px-4 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-medium">
                </div>

                <!-- Filter Status -->
                <div class="relative w-full sm:w-40">
                    <select name="status" onchange="this.form.submit()" 
                            class="appearance-none block w-full pl-4 pr-10 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-medium">
                        <option value="">Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="p-3.5 text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg shadow-blue-500/20 hover:from-blue-700 hover:to-indigo-700 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    
                    @if(request()->anyFilled(['search', 'date', 'status']))
                        <a href="{{ route('sekretaris.agenda.index') }}" 
                           class="p-3.5 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-all" 
                           title="Reset Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Search Info -->
        @if(request('search') || request('date') || request('status'))
            <div class="mt-4 flex items-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Menampilkan hasil untuk: 
                @if(request('search')) <span class="text-gray-900 ml-1">"{{ request('search') }}"</span> @endif
                @if(request('date')) <span class="text-gray-900 ml-1">[{{ request('date') }}]</span> @endif
                @if(request('status')) <span class="text-gray-900 ml-1">[{{ ucfirst(request('status')) }}]</span> @endif
            </div>
        @endif
    </div>
    
    <!-- Agendas Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <h3 class="text-lg font-bold text-gray-900">Daftar Rincian Agenda</h3>
            <span class="px-4 py-1.5 bg-white text-[10px] font-bold text-gray-400 uppercase tracking-widest rounded-xl border border-gray-100">{{ $agendas->total() }} Agenda Ditemukan</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50/30">
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal & Kelas</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Informasi Mata Pelajaran</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ruangan</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Guru Pengajar</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($agendas as $agenda)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $agenda->date->translatedFormat('d M Y') }}</div>
                            <div class="mt-1">
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 rounded-md border border-blue-100 uppercase tracking-wider">
                                    {{ $agenda->class->name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $agenda->subject->name ?? '-' }}</div>
                            <div class="text-[11px] font-medium text-gray-500 mt-0.5 truncate max-w-xs">{{ $agenda->title }}</div>
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                            @if($agenda->room)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-indigo-50 border border-indigo-100 text-[11px] font-bold text-indigo-700">
                                    <svg class="w-3 h-3 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $agenda->room }}
                                </span>
                            @else
                                <span class="text-gray-400 text-[11px] font-medium">-</span>
                            @endif
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-[10px] font-black mr-3">
                                    {{ strtoupper(substr($agenda->teacher->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-bold text-gray-700">{{ $agenda->teacher->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-[10px] font-black {{ $agenda->status == 'published' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100' }} rounded-lg border uppercase tracking-widest">
                                {{ $agenda->status == 'published' ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-3">
                                <button onclick="previewAgenda({{ $agenda->id }})" 
                                        class="text-gray-400 hover:text-blue-600 transition-colors" title="Preview">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <a href="{{ route('sekretaris.agenda.edit', $agenda) }}" 
                                   class="text-gray-400 hover:text-green-600 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('sekretaris.agenda.destroy', $agenda) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus agenda ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Belum Ada Agenda</h3>
                                <p class="text-gray-500 mt-1 mb-4">Mulai kelola agenda kelas Anda hari ini.</p>
                                <a href="{{ route('sekretaris.agenda.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-sm">Buat Agenda Sekarang</a>
                            </div>
                        </td>
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

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500/50 transition-opacity" onclick="closePreview()"></div>
        <div class="relative z-10 inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div id="previewContent" class="bg-white p-8">
                <div class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-4 border-blue-600"></div>
                    <p class="mt-4 text-gray-500 font-bold text-sm tracking-widest uppercase">Memuat Agenda...</p>
                </div>
            </div>
            <div class="bg-gray-50 px-8 py-5 flex justify-end">
                <button onclick="closePreview()" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition-all text-sm">Tutup Preview</button>
            </div>
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
            .then(response => response.json())
            .then(data => {
                content.innerHTML = `
                    <div class="space-y-6">
                        <div class="flex items-start justify-between border-b border-gray-50 pb-6">
                            <div class="max-w-[75%]">
                                <h3 class="text-2xl font-black text-gray-900 leading-tight">${data.title}</h3>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-black rounded-lg border border-blue-100 uppercase tracking-widest">${data.subject_name || 'Umum'}</span>
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-black rounded-lg border border-blue-100 uppercase tracking-widest">${data.class_name}</span>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-[10px] font-black ${data.status === 'published' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100'} rounded-lg border uppercase tracking-widest">
                                ${data.status === 'published' ? 'Published' : 'Draft'}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-6 py-2">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">📅 Tanggal</p>
                                <p class="text-sm font-bold text-gray-700">${data.date}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">👨‍🏫 Guru Pengajar</p>
                                <p class="text-sm font-bold text-gray-700">${data.teacher_name}</p>
                            </div>
                            ${data.room ? `<div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">🏫 Ruangan</p>
                                <p class="text-sm font-bold text-indigo-700">${data.room}</p>
                            </div>` : ''}
                        </div>

                        <div class="space-y-3">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">📝 Deskripsi / Materi</p>
                            <div class="bg-gray-50 rounded-2xl p-6 text-sm text-gray-600 font-medium leading-relaxed prose prose-sm max-w-none border border-gray-100">
                                ${data.description}
                            </div>
                        </div>

                        ${data.attachments ? `
                        <div class="mt-6">
                             <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">📎 Lampiran Dokumen</p>
                            <a href="${data.attachments}" class="flex items-center px-6 py-4 bg-white border border-blue-100 rounded-2xl text-blue-700 hover:bg-blue-50 transition-all shadow-sm group" target="_blank">
                                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold truncate">Unduh Dokumen Lampiran</p>
                                    <p class="text-[10px] font-medium text-gray-400">Klik untuk melihat file</p>
                                </div>
                                <svg class="w-4 h-4 text-blue-300 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                        ` : ''}
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