@extends('layouts.app')

@section('title', 'Jadwal Kegiatan Sekolah')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Jadwal Kegiatan Sekolah</h2>
            <p class="text-sm text-slate-500 mt-1">Agenda upacara, rapat guru, perlombaan, dan pemakaian ruangan khusus.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('kegiatan-sekolah.cetak') }}" target="_blank" class="inline-flex items-center px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-xs shadow-sm transition">
                <i class="fa-solid fa-print mr-2 text-slate-500"></i> Cetak Agenda
            </a>
            <a href="{{ route('kegiatan-sekolah.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-md shadow-teal-600/20 transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Kegiatan
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-3 items-center justify-between">
        <form action="{{ route('kegiatan-sekolah.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <div class="relative flex-1 md:w-80">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama agenda kegiatan..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <select name="id_ruang" class="text-xs py-2 px-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua Lokasi / Ruang</option>
                @foreach($ruangList as $r)
                    <option value="{{ $r->id_ruang }}" {{ $idRuang == $r->id_ruang ? 'selected' : '' }}>{{ $r->nama_ruang }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-3 py-2 text-xs font-semibold bg-slate-800 text-white rounded-xl hover:bg-slate-700 transition">
                Filter
            </button>
            @if($search || $idRuang)
                <a href="{{ route('kegiatan-sekolah.index') }}" class="px-3 py-2 text-xs font-semibold bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition">
                    Reset
                </a>
            @endif
        </form>

        <div class="text-xs text-slate-500 font-medium">
            Total Kegiatan: <span class="font-bold text-slate-700">{{ $kegiatanList->total() }}</span>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3">Nama Kegiatan</th>
                        <th class="px-5 py-3">Rentang Tanggal</th>
                        <th class="px-5 py-3">Waktu</th>
                        <th class="px-5 py-3">Lokasi / Ruang</th>
                        <th class="px-5 py-3">Penanggung Jawab</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($kegiatanList as $k)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 font-bold text-slate-900">{{ $k->nama_kegiatan }}</td>
                            <td class="px-5 py-3.5 text-xs font-semibold text-slate-700">
                                <div>{{ $k->tanggal_mulai->format('d M Y') }}</div>
                                @if($k->tanggal_mulai != $k->tanggal_selesai)
                                    <div class="text-[11px] text-slate-400">s/d {{ $k->tanggal_selesai->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-mono text-xs text-slate-600">
                                <span class="px-2 py-0.5 rounded bg-slate-100">{{ $k->format_jam }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-xs">
                                @if($k->ruang)
                                    <span class="px-2.5 py-1 rounded-lg bg-teal-50 text-teal-800 font-semibold border border-teal-200">
                                        <i class="fa-solid fa-door-open mr-1"></i> {{ $k->ruang->nama_ruang }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Tanpa Ruangan Khusus</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs font-medium text-slate-800">
                                <i class="fa-solid fa-user text-slate-400 mr-1"></i> {{ $k->penanggungJawab?->nama }}
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('kegiatan-sekolah.edit', $k) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('kegiatan-sekolah.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kegiatan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition">
                                        <i class="fa-solid fa-trash mr-1"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400 text-sm">
                                Belum ada jadwal kegiatan sekolah yang didaftarkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kegiatanList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $kegiatanList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

