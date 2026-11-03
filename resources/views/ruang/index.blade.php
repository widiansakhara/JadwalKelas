@extends('layouts.app')

@section('title', 'Master Ruang')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Master Ruang</h2>
            <p class="text-sm text-slate-500 mt-1">Data ruang kelas, laboratorium, aula, dan kapasitas penggunaan jadwal.</p>
        </div>
        <div>
            <a href="{{ route('ruang.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-md shadow-teal-600/20 transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Ruang
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-3 items-center justify-between">
        <form action="{{ route('ruang.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <div class="relative flex-1 md:w-64">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / kode ruang..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <select name="jenis" class="text-xs py-2 px-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua Jenis Ruang</option>
                @foreach($jenisList as $j)
                    <option value="{{ $j }}" {{ $jenis == $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-3 py-2 text-xs font-semibold bg-slate-800 text-white rounded-xl hover:bg-slate-700 transition">
                Filter
            </button>
            @if($search || $jenis)
                <a href="{{ route('ruang.index') }}" class="px-3 py-2 text-xs font-semibold bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition">
                    Reset
                </a>
            @endif
        </form>
        <div class="text-xs text-slate-500 font-medium">
            Total Ruang: <span class="font-bold text-slate-700">{{ $ruangList->total() }}</span>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3">No</th>
                        <th class="px-5 py-3">Kode Ruang</th>
                        <th class="px-5 py-3">Nama Ruang</th>
                        <th class="px-5 py-3">Jenis Ruang</th>
                        <th class="px-5 py-3">Kapasitas</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($ruangList as $index => $r)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 text-xs text-slate-400">{{ $ruangList->firstItem() + $index }}</td>
                            <td class="px-5 py-3.5 font-bold text-teal-700">
                                <span class="px-2.5 py-1 bg-teal-50 rounded-lg border border-teal-200 text-xs font-mono">
                                    {{ $r->kode_ruang }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-900">{{ $r->nama_ruang }}</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $r->jenis == 'Laboratorium' ? 'bg-purple-100 text-purple-700' : ($r->jenis == 'Aula' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $r->jenis }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs font-medium text-slate-600">
                                <i class="fa-solid fa-users text-slate-400 mr-1"></i> {{ $r->kapasitas }} orang
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('jadwal-ruang.index', ['id_ruang' => $r->id_ruang]) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-50 text-sky-700 hover:bg-sky-100 transition" title="Lihat Jadwal Ruang">
                                    <i class="fa-solid fa-calendar-days mr-1"></i> Jadwal
                                </a>
                                <a href="{{ route('ruang.edit', $r) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('ruang.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ruang ini?');">
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
                                Tidak ada data ruang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ruangList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $ruangList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

