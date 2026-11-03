@extends('layouts.app')

@section('title', 'Master Mata Pelajaran')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Master Mata Pelajaran</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar mata pelajaran, alokasi jam per minggu, dan guru pengampu (Relasi Guru-Mapel).</p>
        </div>
        <div>
            <a href="{{ route('mata-pelajaran.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-md shadow-teal-600/20 transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Mata Pelajaran
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-3 items-center justify-between">
        <form action="{{ route('mata-pelajaran.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <div class="relative flex-1 md:w-80">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau kode mata pelajaran..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <button type="submit" class="px-3 py-2 text-xs font-semibold bg-slate-800 text-white rounded-xl hover:bg-slate-700 transition">
                Cari
            </button>
            @if($search)
                <a href="{{ route('mata-pelajaran.index') }}" class="px-3 py-2 text-xs font-semibold bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition">
                    Reset
                </a>
            @endif
        </form>
        <div class="text-xs text-slate-500 font-medium">
            Total Mapel: <span class="font-bold text-slate-700">{{ $mapelList->total() }}</span>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3">No</th>
                        <th class="px-5 py-3">Kode Mapel</th>
                        <th class="px-5 py-3">Nama Mata Pelajaran</th>
                        <th class="px-5 py-3">Beban Belajar</th>
                        <th class="px-5 py-3">Guru Pengampu</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($mapelList as $index => $m)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 text-xs text-slate-400">{{ $mapelList->firstItem() + $index }}</td>
                            <td class="px-5 py-3.5 font-bold text-teal-700">
                                <span class="px-2.5 py-1 bg-teal-50 rounded-lg border border-teal-200 text-xs font-mono">
                                    {{ $m->kode_mapel }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">{{ $m->nama_mapel }}</td>
                            <td class="px-5 py-3.5 text-xs font-medium text-slate-600">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold">
                                    <i class="fa-regular fa-clock text-slate-400 mr-1"></i> {{ $m->jam_per_minggu }} jam / minggu
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($m->guru as $g)
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-md text-[11px] font-medium">
                                            {{ $g->nama }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-amber-500 italic">Belum ada pengampu</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('mata-pelajaran.edit', $m) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('mata-pelajaran.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mata pelajaran ini?');">
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
                                Tidak ada data mata pelajaran ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($mapelList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $mapelList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

