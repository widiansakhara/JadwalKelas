@extends('layouts.app')

@section('title', 'Deteksi & Log Bentrok Otomatis')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-2.5"></i>
                Deteksi Bentrok Jadwal Otomatis & Log
            </h2>
            <p class="text-sm text-slate-500 mt-1">Sistem pencegahan dan audit log otomatis saat terjadi tabrakan jadwal guru, ruang, atau kelas.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('log-bentrok.cetak', request()->query()) }}" target="_blank" class="inline-flex items-center px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-xs shadow-sm transition">
                <i class="fa-solid fa-print mr-2 text-slate-500"></i> Cetak Laporan Bentrok
            </a>
            <form action="{{ route('log-bentrok.pindai') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs shadow-md shadow-teal-600/20 transition">
                    <i class="fa-solid fa-radar mr-2"></i> Pindai Seluruh Jadwal Sekarang
                </button>
            </form>
            @if($logList->total() > 0)
                <form action="{{ route('log-bentrok.clear') }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan seluruh riwayat log bentrok?');">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-600 font-semibold text-xs transition">
                        <i class="fa-solid fa-broom mr-1.5"></i> Bersihkan Log
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Rule Banner -->
    <div class="p-4 bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl text-white shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <span class="w-10 h-10 rounded-xl bg-teal-500/20 border border-teal-500/40 text-teal-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-microchip"></i>
            </span>
            <div>
                <h4 class="font-bold text-sm text-white">Prinsip Deteksi Bentrok Otomatis (Automatic Conflict Detection Engine)</h4>
                <p class="text-xs text-slate-300 mt-0.5">Guru A tidak boleh mendapatkan dua kelas pada jam yang sama. Ruang tidak boleh diisi dua sesi secara bersamaan. Upaya pelanggaran otomatis ditolak dan dicatat di sini.</p>
            </div>
        </div>
        <div class="text-right">
            <span class="text-xs text-slate-400">Total Konflik Tercatat:</span>
            <div class="text-2xl font-black text-amber-400">{{ $logList->total() }}</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('log-bentrok.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Jenis Bentrok</label>
                <select name="jenis_bentrok" class="text-xs py-2 px-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Bentrok</option>
                    @foreach($jenisBentrokOptions as $jb)
                        <option value="{{ $jb }}" {{ $jenisBentrok == $jb ? 'selected' : '' }}>{{ $jb }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Modul Jadwal</label>
                <select name="jenis_jadwal" class="text-xs py-2 px-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Modul</option>
                    @foreach($jenisJadwalOptions as $jj)
                        <option value="{{ $jj }}" {{ $jenisJadwal == $jj ? 'selected' : '' }}>{{ $jj }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Rentang Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="text-xs py-1.5 px-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Rentang Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" class="text-xs py-1.5 px-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="self-end space-x-1">
                <button type="submit" class="px-3.5 py-2 text-xs font-semibold bg-slate-800 text-white rounded-xl hover:bg-slate-700 transition">
                    Filter
                </button>
                @if($jenisBentrok || $jenisJadwal || $tanggalMulai || $tanggalSelesai)
                    <a href="{{ route('log-bentrok.index') }}" class="px-3 py-2 text-xs font-semibold bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition inline-block">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3">Waktu Deteksi</th>
                        <th class="px-5 py-3">Jenis Bentrok</th>
                        <th class="px-5 py-3">Modul Jadwal</th>
                        <th class="px-5 py-3">Deskripsi Kejadian Bentrok</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($logList as $l)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-bold text-slate-900 text-xs">{{ $l->waktu_deteksi->format('d M Y') }}</div>
                                <div class="font-mono text-[11px] text-slate-400">{{ $l->waktu_deteksi->format('H:i:s') }}</div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $l->jenis_bentrok == 'BENTROK GURU' ? 'bg-red-100 text-red-700 border border-red-200' : ($l->jenis_bentrok == 'BENTROK RUANG' ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-purple-100 text-purple-700 border border-purple-200') }}">
                                    <i class="fa-solid fa-triangle-exclamation mr-1 text-[10px]"></i>
                                    {{ $l->jenis_bentrok }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs font-medium">
                                    {{ str_replace('_', ' ', $l->jenis_jadwal) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-800 leading-relaxed font-medium">
                                {{ $l->deskripsi }}
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <form action="{{ route('log-bentrok.destroy', $l) }}" method="POST" class="inline" onsubmit="return confirm('Hapus entri log ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Hapus Log">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2 text-xl">
                                    <i class="fa-solid fa-check-double"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Tidak ada data bentrok jadwal</p>
                                <p class="text-xs text-slate-400 mt-0.5">Sistem berjalan dengan aman dan semua jadwal terkoordinasi rapi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $logList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

