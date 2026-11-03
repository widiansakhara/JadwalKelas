<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalGuruController extends Controller
{
    public function index(Request $request): View
    {
        $guruList = Guru::orderBy('nama')->get();
        $idGuru = $request->query('id_guru', $guruList->first()?->id_guru);

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

        if ($idGuru && $idTahunAjaran) {
            $jadwalList = JadwalPelajaran::with(['kelas', 'mataPelajaran', 'ruang', 'jamPelajaran'])
                ->where('id_tahun_ajaran', $idTahunAjaran)
                ->where('id_guru', $idGuru)
                ->get();

            foreach ($jadwalList as $jadwal) {
                if (isset($matriksJadwal[$jadwal->hari])) {
                    $matriksJadwal[$jadwal->hari][$jadwal->id_jam] = $jadwal;
                }
            }
        }

        $guruTerpilih = $guruList->firstWhere('id_guru', $idGuru);
        $tahunTerpilih = $tahunList->firstWhere('id_tahun_ajaran', $idTahunAjaran);
        $totalJamMengajar = $jadwalList->count();

        return view('jadwal_guru.index', compact(
            'guruList',
            'guruTerpilih',
            'idGuru',
            'tahunList',
            'tahunTerpilih',
            'idTahunAjaran',
            'jamList',
            'hariList',
            'matriksJadwal',
            'totalJamMengajar',
            'jadwalList'
        ));
    }

    public function cetak(Request $request): View
    {
        $guruList = Guru::orderBy('nama')->get();
        $idGuru = $request->query('id_guru', $guruList->first()?->id_guru);

        $tahunList = TahunAjaran::orderByDesc('tahun')->get();
        $tahunAktif = TahunAjaran::where('status_aktif', true)->first() ?? $tahunList->first();
        $idTahunAjaran = $request->query('id_tahun_ajaran', $tahunAktif?->id_tahun_ajaran);

        $jamList = JamPelajaran::orderBy('jam_ke')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $jadwalList = JadwalPelajaran::with(['kelas', 'mataPelajaran', 'ruang', 'jamPelajaran'])
            ->where('id_tahun_ajaran', $idTahunAjaran)
            ->where('id_guru', $idGuru)
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

        $guruTerpilih = $guruList->firstWhere('id_guru', $idGuru);
        $tahunTerpilih = $tahunList->firstWhere('id_tahun_ajaran', $idTahunAjaran);
        $totalJamMengajar = $jadwalList->count();

        return view('jadwal_guru.cetak', compact(
            'guruTerpilih',
            'tahunTerpilih',
            'jamList',
            'hariList',
            'matriksJadwal',
            'totalJamMengajar'
        ));
    }
}

