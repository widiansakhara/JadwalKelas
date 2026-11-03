@extends('layouts.app')

@section('title', 'Edit Master Ruang')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Master Ruang</h2>
            <p class="text-sm text-slate-500 mt-1">Ubah data ruangan: {{ $ruang->nama_ruang }} ({{ $ruang->kode_ruang }}).</p>
        </div>
        <a href="{{ route('ruang.index') }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('ruang.update', $ruang) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Ruang <span class="text-red-500">*</span></label>
                <input type="text" name="kode_ruang" value="{{ old('kode_ruang', $ruang->kode_ruang) }}" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Ruang <span class="text-red-500">*</span></label>
                <input type="text" name="nama_ruang" value="{{ old('nama_ruang', $ruang->nama_ruang) }}" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Ruang <span class="text-red-500">*</span></label>
                    <select name="jenis" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach(['Teori', 'Laboratorium', 'Aula', 'Perpustakaan', 'Bengkel'] as $j)
                            <option value="{{ $j }}" {{ old('jenis', $ruang->jenis) == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kapasitas (Siswa) <span class="text-red-500">*</span></label>
                    <input type="number" name="kapasitas" value="{{ old('kapasitas', $ruang->kapasitas) }}" min="1" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('ruang.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow-md shadow-teal-600/20 transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Perbarui Data Ruang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

