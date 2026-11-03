@extends('layouts.app')

@section('title', 'Jadwal Ujian & Pengawas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Jadwal Ujian & Pengawas</h2>
            <p class="text-sm text-slate-500 mt-1">Penjadwalan UTS, UAS, Ulangan Harian, dan penetapan guru pengawas ujian.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('jadwal-ujian.cetak', ['id_tahun_ajaran' => $idTahunAjaran, 'id_kelas' => $idKelas, 'jenis_ujian' => $jenisUjian]) }}" target="_blank" class="inline-flex items-center px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-xs shadow-sm transition">
                <i class="fa-solid fa-print mr-2 text-slate-500"></i> Cetak Jadwal Ujian
            </a>
            <a href="{{ route('jadwal-ujian.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-md shadow-teal-600/20 transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Jadwal Ujian
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-3 items-center justify-between">
        <form action="{{ route('jadwal-ujian.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Tahun Ajaran</label>
                <select name="id_tahun_ajaran" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 font-medium focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach($tahunList as $ta)
                        <option value="{{ $ta->id_tahun_ajaran }}" {{ $idTahunAjaran == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                            {{ $ta->tahun }} ({{ $ta->semester }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Filter Kelas</label>
                <select name="id_kelas" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id_kelas }}" {{ $idKelas == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Jenis Ujian</label>
                <select name="jenis_ujian" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Jenis</option>
                    @foreach(['UTS', 'UAS', 'Ulangan Harian', 'Try Out'] as $ju)
                        <option value="{{ $ju }}" {{ $jenisUjian == $ju ? 'selected' : '' }}>{{ $ju }}</option>
                    @endforeach
                </select>
            </div>

            @if($idKelas || $jenisUjian)
                <div class="self-end">
                    <a href="{{ route('jadwal-ujian.index') }}" class="px-3 py-2 text-xs font-semibold bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition inline-block">
                        Reset
                    </a>
                </div>
            @endif
        </form>

        <div class="text-xs text-slate-500 font-medium">
            Total Sesi Ujian: <span class="font-bold text-teal-700">{{ $ujianList->total() }}</span>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3">Tanggal & Waktu</th>
                        <th class="px-5 py-3">Jenis Ujian</th>
                        <th class="px-5 py-3">Kelas</th>
                        <th class="px-5 py-3">Mata Pelajaran</th>
                        <th class="px-5 py-3">Ruang</th>
                        <th class="px-5 py-3">Guru Pengawas</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($ujianList as $u)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-slate-900">{{ $u->tanggal->format('d M Y') }}</div>
                                <div class="font-mono text-xs text-slate-500">{{ $u->format_jam }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-md text-xs font-extrabold {{ $u->jenis_ujian == 'UTS' ? 'bg-amber-100 text-amber-800' : ($u->jenis_ujian == 'UAS' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $u->jenis_ujian }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-800">{{ $u->kelas?->nama_kelas }}</td>
                            <td class="px-5 py-3.5 font-semibold text-teal-800">{{ $u->mataPelajaran?->nama_mapel }}</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-xs font-semibold text-slate-700">
                                    {{ $u->ruang?->kode_ruang }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($u->pengawas as $p)
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded text-xs font-medium">
                                            <i class="fa-solid fa-user-check text-[10px] mr-1 text-emerald-600"></i>{{ $p->nama }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-amber-500 italic">Belum ada pengawas</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('jadwal-ujian.edit', $u) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('jadwal-ujian.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Hapus sesi jadwal ujian ini?');">
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
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">
                                Belum ada jadwal ujian yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ujianList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $ujianList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

