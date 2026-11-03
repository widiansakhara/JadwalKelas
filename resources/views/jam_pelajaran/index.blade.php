@extends('layouts.app')

@section('title', 'Master Jam Pelajaran')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Master Jam Pelajaran</h2>
            <p class="text-sm text-slate-500 mt-1">Konfigurasi slot jam belajar mengajar sekolah (Jam Ke-1, Ke-2, dst).</p>
        </div>
        <div>
            <a href="{{ route('jam-pelajaran.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-md shadow-teal-600/20 transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Jam Pelajaran
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3">Jam Ke</th>
                        <th class="px-5 py-3">Jam Mulai</th>
                        <th class="px-5 py-3">Jam Selesai</th>
                        <th class="px-5 py-3">Rentang Waktu</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($jamList as $j)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 font-bold text-teal-700">
                                <span class="w-8 h-8 rounded-full bg-teal-50 border border-teal-200 flex items-center justify-center font-bold text-xs">
                                    {{ $j->jam_ke }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-xs font-semibold text-slate-800">{{ substr($j->jam_mulai, 0, 5) }}</td>
                            <td class="px-5 py-3.5 font-mono text-xs font-semibold text-slate-800">{{ substr($j->jam_selesai, 0, 5) }}</td>
                            <td class="px-5 py-3.5 text-xs font-semibold text-slate-600">
                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg">
                                    <i class="fa-regular fa-clock text-slate-400 mr-1"></i> {{ $j->format_jam }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('jam-pelajaran.edit', $j) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('jam-pelajaran.destroy', $j) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data jam pelajaran ini?');">
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
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400 text-sm">
                                Belum ada slot jam pelajaran yang didaftarkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

