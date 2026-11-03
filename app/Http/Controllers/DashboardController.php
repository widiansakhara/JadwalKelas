<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\JadwalUjian;
use App\Models\KegiatanSekolah;
use App\Models\Kelas;
use App\Models\LogBentrok;
use App\Models\MataPelajaran;
use App\Models\Ruang;
use App\Models\TahunAjaran;
use App\Services\ConflictDetectionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected ConflictDetectionService $conflictService
    ) {}

    public function index(): View
    {
        $tahunAktif = TahunAjaran::where('status_aktif', true)->first();

        $stats = [
            'guru'             => Guru::count(),
            'ruang'            => Ruang::count(),
            'mata_pelajaran'   => MataPelajaran::count(),
            'kelas'            => Kelas::count(),
            'jadwal_pelajaran' => JadwalPelajaran::when($tahunAktif, fn($q) => $q->where('id_tahun_ajaran', $tahunAktif->id_tahun_ajaran))->count(),
            'jadwal_ujian'     => JadwalUjian::when($tahunAktif, fn($q) => $q->where('id_tahun_ajaran', $tahunAktif->id_tahun_ajaran))->count(),
            'kegiatan_sekolah' => KegiatanSekolah::count(),
            'log_bentrok'      => LogBentrok::count(),
        ];

        $bentrokTerbaru = LogBentrok::latest('waktu_deteksi')->take(5)->get();
        $kegiatanMendatang = KegiatanSekolah::with(['ruang', 'penanggungJawab'])
            ->where('tanggal_selesai', '>=', now()->toDateString())
            ->orderBy('tanggal_mulai')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'tahunAktif', 'bentrokTerbaru', 'kegiatanMendatang'));
    }
}

