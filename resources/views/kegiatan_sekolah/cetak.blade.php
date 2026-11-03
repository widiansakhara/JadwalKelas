<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda & Kegiatan Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0 !important;
                background: white !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 p-6 min-h-screen">
    <div class="max-w-5xl mx-auto mb-4 no-print flex items-center justify-between">
        <a href="{{ route('kegiatan-sekolah.index') }}" class="px-4 py-2 bg-white rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            &larr; Kembali
        </a>
        <button onclick="window.print()" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold shadow-md shadow-teal-600/20">
            <i class="fa-solid fa-print mr-1.5"></i> Cetak Dokumen / Simpan PDF
        </button>
    </div>

    <div class="max-w-5xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
        <div class="border-b-2 border-slate-900 pb-4 mb-6 text-center">
            <h1 class="text-xl font-extrabold uppercase tracking-wider text-slate-900">SMA / SMK NEGERI 1 AKADEMIK</h1>
            <p class="text-xs text-slate-600 mt-0.5">Jl. Pendidikan No. 123 Kota Pelajar, Telepon (021) 1234567</p>
            <h2 class="text-base font-bold text-teal-800 mt-2 uppercase tracking-wide">AGENDA & KEGIATAN RESMI SEKOLAH</h2>
            <p class="text-xs text-slate-500 font-medium">Laporan Pemakaian Sarana & Alokasi Ruangan Khusus</p>
        </div>

        <table class="w-full border-collapse border border-slate-800 text-xs">
            <thead>
                <tr class="bg-slate-100">
                    <th class="border border-slate-800 p-2 text-center w-10 font-bold">No</th>
                    <th class="border border-slate-800 p-2 text-left font-bold">Nama Kegiatan</th>
                    <th class="border border-slate-800 p-2 text-center font-bold">Tanggal Pelaksanaan</th>
                    <th class="border border-slate-800 p-2 text-center font-bold">Waktu</th>
                    <th class="border border-slate-800 p-2 text-center font-bold">Ruangan Terpakai</th>
                    <th class="border border-slate-800 p-2 text-left font-bold">Penanggung Jawab</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kegiatanList as $index => $k)
                    <tr>
                        <td class="border border-slate-800 p-2 text-center">{{ $index + 1 }}</td>
                        <td class="border border-slate-800 p-2 text-left font-bold text-slate-900">{{ $k->nama_kegiatan }}</td>
                        <td class="border border-slate-800 p-2 text-center font-semibold">
                            {{ $k->tanggal_mulai->format('d M Y') }}
                            @if($k->tanggal_mulai != $k->tanggal_selesai)
                                <br><span class="text-[10px] text-slate-500">s/d {{ $k->tanggal_selesai->format('d M Y') }}</span>
                            @endif
                        </td>
                        <td class="border border-slate-800 p-2 text-center font-mono">{{ $k->format_jam }}</td>
                        <td class="border border-slate-800 p-2 text-center font-semibold text-teal-800">
                            {{ $k->ruang ? $k->ruang->nama_ruang . ' (' . $k->ruang->kode_ruang . ')' : '-' }}
                        </td>
                        <td class="border border-slate-800 p-2 text-left">{{ $k->penanggungJawab?->nama }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border border-slate-800 p-4 text-center italic text-slate-400">Belum ada agenda kegiatan yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-12 flex justify-between text-xs text-slate-700">
            <div class="text-center">
                <p>Koordinator Kesiswaan / Kegiatan,</p>
                <div class="h-16"></div>
                <p class="font-bold underline">( ............................................ )</p>
                <p>NIP. </p>
            </div>
            <div class="text-center">
                <p>Mengetahui,</p>
                <p>Kepala Sekolah,</p>
                <div class="h-14"></div>
                <p class="font-bold underline">( Dr. H. Mulyadi, M.Pd )</p>
                <p>NIP. 196805121994031002</p>
            </div>
        </div>
    </div>
</body>
</html>

