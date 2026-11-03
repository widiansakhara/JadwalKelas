<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\JadwalUjian;
use App\Models\JamPelajaran;
use App\Models\KegiatanSekolah;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalSiswaController extends Controller
{
    public function index(Request $request): View
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $idKelas = $request->query('id_kelas', $kelasList->first()?->id_kelas);

        $tahunList = TahunAjaran::orderByDesc('tahun')->get();
        $tahunAktif = TahunAjaran::where('status_aktif', true)->first() ?? $tahunList->first();
        $idTahunAjaran = $request->query('id_tahun_ajaran', $tahunAktif?->id_tahun_ajaran);

        $jamList = JamPelajaran::orderBy('jam_ke')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // Mendeteksi nama hari ini dalam Bahasa Indonesia
        $hariMap = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];
        $hariIni = $hariMap[Carbon::now()->dayOfWeekIso] ?? 'Senin';

        // Jadwal Pelajaran Kelas Terpilih
        $jadwalList = collect();
        $matriksJadwal = [];

        foreach ($hariList as $h) {
            $matriksJadwal[$h] = [];
            foreach ($jamList as $j) {
                $matriksJadwal[$h][$j->id_jam] = null;
            }
        }

        $jadwalHariIni = collect();

        if ($idKelas && $idTahunAjaran) {
            $jadwalList = JadwalPelajaran::with(['mataPelajaran', 'guru', 'ruang', 'jamPelajaran'])
                ->where('id_tahun_ajaran', $idTahunAjaran)
                ->where('id_kelas', $idKelas)
                ->get();

            foreach ($jadwalList as $jadwal) {
                if (isset($matriksJadwal[$jadwal->hari])) {
                    $matriksJadwal[$jadwal->hari][$jadwal->id_jam] = $jadwal;
                }
            }

            // Jadwal khusus hari ini untuk quick view murid
            $jadwalHariIni = $jadwalList->where('hari', $hariIni)->sortBy('jamPelajaran.jam_ke');
        }

        // Jadwal Ujian untuk Kelas Terpilih
        $ujianList = JadwalUjian::with(['mataPelajaran', 'ruang', 'pengawas'])
            ->where('id_tahun_ajaran', $idTahunAjaran)
            ->where('id_kelas', $idKelas)
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        // Agenda & Kegiatan Sekolah
        $kegiatanList = KegiatanSekolah::with(['ruang', 'penanggungJawab'])
            ->where('tanggal_selesai', '>=', now()->toDateString())
            ->orderBy('tanggal_mulai')
            ->take(5)
            ->get();

        $kelasTerpilih = $kelasList->firstWhere('id_kelas', $idKelas);
        $tahunTerpilih = $tahunList->firstWhere('id_tahun_ajaran', $idTahunAjaran);

        return view('portal.siswa', compact(
            'kelasList',
            'kelasTerpilih',
            'idKelas',
            'tahunList',
            'tahunTerpilih',
            'idTahunAjaran',
            'jamList',
            'hariList',
            'hariIni',
            'matriksJadwal',
            'jadwalList',
            'jadwalHariIni',
            'ujianList',
            'kegiatanList'
        ));
    }

    public function cetak(Request $request): View
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $idKelas = $request->query('id_kelas', $kelasList->first()?->id_kelas);

        $tahunList = TahunAjaran::orderByDesc('tahun')->get();
        $tahunAktif = TahunAjaran::where('status_aktif', true)->first() ?? $tahunList->first();
        $idTahunAjaran = $request->query('id_tahun_ajaran', $tahunAktif?->id_tahun_ajaran);

        $jamList = JamPelajaran::orderBy('jam_ke')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $jadwalList = JadwalPelajaran::with(['mataPelajaran', 'guru', 'ruang', 'jamPelajaran'])
            ->where('id_tahun_ajaran', $idTahunAjaran)
            ->where('id_kelas', $idKelas)
            ->get();

        $matriksJadwal = [];
        foreach ($hariList as $h) {
            $matriksJadwal[$h] = [];
            foreach ($jamList as $j) {
                $matriksJadwal[$h][$j->id_jam] = null;
            }
        }

        foreach ($jadwalList as $jadwal) {
            if (isset($matriksJadwal[$jadwal->hari])) {
                $matriksJadwal[$jadwal->hari][$jadwal->id_jam] = $jadwal;
            }
        }

        $kelasTerpilih = $kelasList->firstWhere('id_kelas', $idKelas);
        $tahunTerpilih = $tahunList->firstWhere('id_tahun_ajaran', $idTahunAjaran);

        return view('portal.cetak', compact(
            'kelasTerpilih',
            'tahunTerpilih',
            'jamList',
            'hariList',
            'matriksJadwal'
        ));
    }
}

