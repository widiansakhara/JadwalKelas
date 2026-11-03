<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Mengajar - {{ $guruTerpilih?->nama }}</title>
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
        <a href="{{ route('jadwal-guru.index', ['id_guru' => $guruTerpilih?->id_guru]) }}" class="px-4 py-2 bg-white rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
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
            <h2 class="text-base font-bold text-teal-800 mt-2 uppercase tracking-wide">JADWAL MENGAJAR GURU</h2>
            <p class="text-sm font-bold text-slate-800">{{ $guruTerpilih?->nama }} (NIP. {{ $guruTerpilih?->nip }})</p>
            <p class="text-xs text-slate-500 font-medium">Tahun Ajaran: {{ $tahunTerpilih?->tahun }} (Semester {{ $tahunTerpilih?->semester }}) | Total Beban: {{ $totalJamMengajar }} Jam Pelajaran / Minggu</p>
        </div>

        <table class="w-full border-collapse border border-slate-800 text-xs">
            <thead>
                <tr class="bg-slate-100">
                    <th class="border border-slate-800 p-2 text-center w-24 font-bold">Jam / Waktu</th>
                    @foreach($hariList as $hari)
                        <th class="border border-slate-800 p-2 text-center font-bold uppercase">{{ $hari }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($jamList as $jam)
                    <tr>
                        <td class="border border-slate-800 p-2 text-center bg-slate-50 font-semibold">
                            <div>Jam Ke-{{ $jam->jam_ke }}</div>
                            <div class="text-[10px] text-slate-500">{{ $jam->format_jam }}</div>
                        </td>
                        @foreach($hariList as $hari)
                            @php
                                $slot = $matriksJadwal[$hari][$jam->id_jam] ?? null;
                            @endphp
                            <td class="border border-slate-800 p-2 text-center align-middle h-16">
                                @if($slot)
                                    <div class="font-bold text-slate-900 leading-tight">{{ $slot->mataPelajaran?->nama_mapel }}</div>
                                    <div class="text-[10px] text-blue-700 font-bold mt-0.5">{{ $slot->kelas?->nama_kelas }}</div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Ruang {{ $slot->ruang?->kode_ruang }}</div>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-12 flex justify-between text-xs text-slate-700">
            <div class="text-center">
                <p>Guru Pengajar,</p>
                <div class="h-16"></div>
                <p class="font-bold underline">{{ $guruTerpilih?->nama }}</p>
                <p>NIP. {{ $guruTerpilih?->nip }}</p>
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

