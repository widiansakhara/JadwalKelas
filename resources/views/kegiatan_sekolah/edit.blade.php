@extends('layouts.app')

@section('title', 'Edit Kegiatan Sekolah')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Kegiatan Sekolah</h2>
            <p class="text-sm text-slate-500 mt-1">Ubah rincian agenda: {{ $kegiatanSekolah->nama_kegiatan }}.</p>
        </div>
        <a href="{{ route('kegiatan-sekolah.index') }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('kegiatan-sekolah.update', $kegiatanSekolah) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Agenda Kegiatan <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $kegiatanSekolah->nama_kegiatan) }}" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Rentang Tanggal -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $kegiatanSekolah->tanggal_mulai->format('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $kegiatanSekolah->tanggal_selesai->format('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <!-- Rentang Jam -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', substr($kegiatanSekolah->jam_mulai, 0, 5)) }}" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', substr($kegiatanSekolah->jam_selesai, 0, 5)) }}" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <!-- Ruang Lokasi -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ruangan / Tempat yang Digunakan</label>
                <select name="id_ruang" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">-- Tanpa Memesan Ruangan Khusus --</option>
                    @foreach($ruangList as $r)
                        <option value="{{ $r->id_ruang }}" {{ old('id_ruang', $kegiatanSekolah->id_ruang) == $r->id_ruang ? 'selected' : '' }}>
                            {{ $r->kode_ruang }} - {{ $r->nama_ruang }} ({{ $r->jenis }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Penanggung Jawab -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Guru Penanggung Jawab <span class="text-red-500">*</span></label>
                <select name="id_penanggung_jawab" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach($guruList as $g)
                        <option value="{{ $g->id_guru }}" {{ old('id_penanggung_jawab', $kegiatanSekolah->id_penanggung_jawab) == $g->id_guru ? 'selected' : '' }}>
                            {{ $g->nama }} (NIP: {{ $g->nip }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('kegiatan-sekolah.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow-md shadow-teal-600/20 transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Perbarui Kegiatan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

