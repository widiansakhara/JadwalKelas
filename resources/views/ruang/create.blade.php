@extends('layouts.app')

@section('title', 'Tambah Master Ruang')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Tambah Master Ruang</h2>
            <p class="text-sm text-slate-500 mt-1">Tambahkan informasi ruangan baru untuk alokasi jadwal belajar & ujian.</p>
        </div>
        <a href="{{ route('ruang.index') }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('ruang.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Ruang <span class="text-red-500">*</span></label>
                <input type="text" name="kode_ruang" value="{{ old('kode_ruang') }}" required placeholder="Contoh: R-101, LAB-IPA, AULA" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                <p class="text-[11px] text-slate-400 mt-1">Harus unik dan maksimal 10 karakter.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Ruang <span class="text-red-500">*</span></label>
                <input type="text" name="nama_ruang" value="{{ old('nama_ruang') }}" required placeholder="Contoh: Ruang Kelas X MIPA 1" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Ruang <span class="text-red-500">*</span></label>
                    <select name="jenis" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="Teori" {{ old('jenis') == 'Teori' ? 'selected' : '' }}>Teori / Kelas</option>
                        <option value="Laboratorium" {{ old('jenis') == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                        <option value="Aula" {{ old('jenis') == 'Aula' ? 'selected' : '' }}>Aula / Gedung Serbaguna</option>
                        <option value="Perpustakaan" {{ old('jenis') == 'Perpustakaan' ? 'selected' : '' }}>Perpustakaan</option>
                        <option value="Bengkel" {{ old('jenis') == 'Bengkel' ? 'selected' : '' }}>Bengkel / Praktik</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kapasitas (Siswa) <span class="text-red-500">*</span></label>
                    <input type="number" name="kapasitas" value="{{ old('kapasitas', 36) }}" min="1" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('ruang.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow-md shadow-teal-600/20 transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Simpan Data Ruang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

