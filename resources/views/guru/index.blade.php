@extends('layouts.app')

@section('title', 'Master Guru')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Master Guru</h2>
            <p class="text-sm text-slate-500 mt-1">Data tenaga pendidik, NIP, kontak, serta penugasan mata pelajaran yang diampu.</p>
        </div>
        <div>
            <a href="{{ route('guru.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-md shadow-teal-600/20 transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Guru
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-3 items-center justify-between">
        <form action="{{ route('guru.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <div class="relative flex-1 md:w-80">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIP, atau email..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <button type="submit" class="px-3 py-2 text-xs font-semibold bg-slate-800 text-white rounded-xl hover:bg-slate-700 transition">
                Cari
            </button>
            @if($search)
                <a href="{{ route('guru.index') }}" class="px-3 py-2 text-xs font-semibold bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition">
                    Reset
                </a>
            @endif
        </form>
        <div class="text-xs text-slate-500 font-medium">
            Total Guru: <span class="font-bold text-slate-700">{{ $guruList->total() }}</span>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3">No</th>
                        <th class="px-5 py-3">NIP</th>
                        <th class="px-5 py-3">Nama Lengkap</th>
                        <th class="px-5 py-3">Kontak</th>
                        <th class="px-5 py-3">Mapel Diampu</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($guruList as $index => $g)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 text-xs text-slate-400">{{ $guruList->firstItem() + $index }}</td>
                            <td class="px-5 py-3.5 font-mono text-xs font-semibold text-slate-700">{{ $g->nip }}</td>
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-slate-900">{{ $g->nama }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600 space-y-0.5">
                                <div><i class="fa-regular fa-envelope text-slate-400 mr-1"></i> {{ $g->email }}</div>
                                <div><i class="fa-solid fa-phone text-slate-400 mr-1"></i> {{ $g->no_hp }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($g->mataPelajaran as $m)
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-md text-[11px] font-medium">
                                            {{ $m->nama_mapel }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400 italic">Belum diatur</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('jadwal-guru.index', ['id_guru' => $g->id_guru]) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-50 text-sky-700 hover:bg-sky-100 transition" title="Lihat Jadwal Mengajar Guru">
                                    <i class="fa-solid fa-calendar-days mr-1"></i> Jadwal
                                </a>
                                <a href="{{ route('guru.edit', $g) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('guru.destroy', $g) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition">
                                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400 text-sm">
                                Tidak ada data guru ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($guruList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $guruList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

