<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\KegiatanSekolah;
use App\Models\Ruang;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalRuangController extends Controller
{
    public function index(Request $request): View
    {
        $ruangList = Ruang::orderBy('kode_ruang')->get();
        $idRuang = $request->query('id_ruang', $ruangList->first()?->id_ruang);

        $tahunList = TahunAjaran::orderByDesc('tahun')->get();
        $tahunAktif = TahunAjaran::where('status_aktif', true)->first() ?? $tahunList->first();
        $idTahunAjaran = $request->query('id_tahun_ajaran', $tahunAktif?->id_tahun_ajaran);

        $jamList = JamPelajaran::orderBy('jam_ke')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $jadwalList = collect();
        $matriksJadwal = [];

        foreach ($hariList as $h) {
            $matriksJadwal[$h] = [];
            foreach ($jamList as $j) {
                $matriksJadwal[$h][$j->id_jam] = null;
            }
        }

        $kegiatanRuang = collect();

        if ($idRuang && $idTahunAjaran) {
            $jadwalList = JadwalPelajaran::with(['kelas', 'mataPelajaran', 'guru', 'jamPelajaran'])
                ->where('id_tahun_ajaran', $idTahunAjaran)
                ->where('id_ruang', $idRuang)
                ->get();

            foreach ($jadwalList as $jadwal) {
                if (isset($matriksJadwal[$jadwal->hari])) {
                    $matriksJadwal[$jadwal->hari][$jadwal->id_jam] = $jadwal;
                }
            }

            $kegiatanRuang = KegiatanSekolah::with('penanggungJawab')
                ->where('id_ruang', $idRuang)
                ->where('tanggal_selesai', '>=', now()->toDateString())
                ->orderBy('tanggal_mulai')
                ->get();
        }

        $ruangTerpilih = $ruangList->firstWhere('id_ruang', $idRuang);
        $tahunTerpilih = $tahunList->firstWhere('id_tahun_ajaran', $idTahunAjaran);
        $totalPenggunaan = $jadwalList->count();

        return view('jadwal_ruang.index', compact(
            'ruangList',
            'ruangTerpilih',
            'idRuang',
            'tahunList',
            'tahunTerpilih',
            'idTahunAjaran',
            'jamList',
            'hariList',
            'matriksJadwal',
            'totalPenggunaan',
            'kegiatanRuang',
            'jadwalList'
        ));
    }

    public function cetak(Request $request): View
    {
        $ruangList = Ruang::orderBy('kode_ruang')->get();
        $idRuang = $request->query('id_ruang', $ruangList->first()?->id_ruang);

        $tahunList = TahunAjaran::orderByDesc('tahun')->get();
        $tahunAktif = TahunAjaran::where('status_aktif', true)->first() ?? $tahunList->first();
        $idTahunAjaran = $request->query('id_tahun_ajaran', $tahunAktif?->id_tahun_ajaran);

        $jamList = JamPelajaran::orderBy('jam_ke')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $jadwalList = JadwalPelajaran::with(['kelas', 'mataPelajaran', 'guru', 'jamPelajaran'])
            ->where('id_tahun_ajaran', $idTahunAjaran)
            ->where('id_ruang', $idRuang)
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

        $ruangTerpilih = $ruangList->firstWhere('id_ruang', $idRuang);
        $tahunTerpilih = $tahunList->firstWhere('id_tahun_ajaran', $idTahunAjaran);
        $totalPenggunaan = $jadwalList->count();

        return view('jadwal_ruang.cetak', compact(
            'ruangTerpilih',
            'tahunTerpilih',
            'jamList',
            'hariList',
            'matriksJadwal',
            'totalPenggunaan'
        ));
    }
}

