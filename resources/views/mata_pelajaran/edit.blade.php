@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Mata Pelajaran</h2>
            <p class="text-sm text-slate-500 mt-1">Ubah kurikulum: {{ $mataPelajaran->nama_mapel }} ({{ $mataPelajaran->kode_mapel }}).</p>
        </div>
        <a href="{{ route('mata-pelajaran.index') }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('mata-pelajaran.update', $mataPelajaran) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Mapel <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_mapel" value="{{ old('kode_mapel', $mataPelajaran->kode_mapel) }}" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jam / Minggu <span class="text-red-500">*</span></label>
                    <input type="number" name="jam_per_minggu" value="{{ old('jam_per_minggu', $mataPelajaran->jam_per_minggu) }}" min="1" max="10" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Mata Pelajaran <span class="text-red-500">*</span></label>
                <input type="text" name="nama_mapel" value="{{ old('nama_mapel', $mataPelajaran->nama_mapel) }}" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            @php
                $assignedGuruIds = $mataPelajaran->guru->pluck('id_guru')->toArray();
            @endphp

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Guru Pengampu yang Berwenang (guru_mapel)</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2 max-h-52 overflow-y-auto p-3 border border-slate-200 rounded-xl bg-slate-50">
                    @forelse($guruList as $g)
                        <label class="flex items-center space-x-2 text-xs text-slate-700 p-1.5 rounded-lg hover:bg-white transition cursor-pointer">
                            <input type="checkbox" name="guru_ids[]" value="{{ $g->id_guru }}" {{ in_array($g->id_guru, old('guru_ids', $assignedGuruIds)) ? 'checked' : '' }} class="rounded text-teal-600 focus:ring-teal-500">
                            <span class="truncate">{{ $g->nama }}</span>
                        </label>
                    @empty
                        <p class="text-xs text-slate-400 col-span-2">Belum ada data guru terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('mata-pelajaran.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow-md shadow-teal-600/20 transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Perbarui Mata Pelajaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

