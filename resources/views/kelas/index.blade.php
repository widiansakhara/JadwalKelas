@extends('layouts.app')

@section('title', 'Master Kelas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Master Kelas</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar rombongan belajar / kelas, tingkat, dan jurusan peminatan.</p>
        </div>
        <div>
            <a href="{{ route('kelas.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-md shadow-teal-600/20 transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Kelas
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3">No</th>
                        <th class="px-5 py-3">Nama Kelas</th>
                        <th class="px-5 py-3">Tingkat</th>
                        <th class="px-5 py-3">Jurusan / Program</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($kelasList as $index => $k)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 text-xs text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                <span class="px-2.5 py-1 bg-purple-50 text-purple-700 rounded-lg border border-purple-200 font-semibold text-xs">
                                    {{ $k->nama_kelas }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs font-semibold text-slate-700">Tingkat {{ $k->tingkat }}</td>
                            <td class="px-5 py-3.5 text-xs text-slate-600">{{ $k->jurusan }}</td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('jadwal-pelajaran.index', ['id_kelas' => $k->id_kelas]) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-50 text-sky-700 hover:bg-sky-100 transition" title="Lihat Jadwal Kelas">
                                    <i class="fa-solid fa-table-list mr-1"></i> Jadwal
                                </a>
                                <a href="{{ route('kelas.edit', $k) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('kelas.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kelas ini?');">
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
                                Belum ada data kelas yang didaftarkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

