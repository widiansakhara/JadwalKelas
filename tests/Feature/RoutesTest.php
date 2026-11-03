<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Ruang;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_get_routes_render_successfully(): void
    {
        $ta = TahunAjaran::create(['tahun' => '2024/2025', 'semester' => 'Ganjil', 'status_aktif' => true]);
        $kelas = Kelas::create(['nama_kelas' => 'X-IPA-1', 'tingkat' => 'X', 'jurusan' => 'MIPA']);
        $guru = Guru::create(['nip' => '198001012005011001', 'nama' => 'Budi Santoso', 'email' => 'budi@sekolah.sch.id', 'no_hp' => '081234567891']);
        $mapel = MataPelajaran::create(['kode_mapel' => 'MAT01', 'nama_mapel' => 'Matematika', 'jam_per_minggu' => 4]);
        $ruang = Ruang::create(['kode_ruang' => 'R-101', 'nama_ruang' => 'Ruang 101', 'jenis' => 'Teori', 'kapasitas' => 36]);
        $jam = JamPelajaran::create(['jam_ke' => 1, 'jam_mulai' => '07:00:00', 'jam_selesai' => '07:45:00']);

        $urls = [
            route('dashboard'),
            route('portal.siswa'),
            route('portal.siswa.cetak'),
            route('ruang.index'),
            route('ruang.create'),
            route('ruang.edit', $ruang),
            route('guru.index'),
            route('guru.create'),
            route('guru.edit', $guru),
            route('mata-pelajaran.index'),
            route('mata-pelajaran.create'),
            route('mata-pelajaran.edit', $mapel),
            route('kelas.index'),
            route('kelas.create'),
            route('kelas.edit', $kelas),
            route('jam-pelajaran.index'),
            route('jam-pelajaran.create'),
            route('jam-pelajaran.edit', $jam),
            route('tahun-ajaran.index'),
            route('tahun-ajaran.create'),
            route('tahun-ajaran.edit', $ta),
            route('jadwal-pelajaran.index'),
            route('jadwal-pelajaran.create'),
            route('jadwal-pelajaran.cetak'),
            route('jadwal-guru.index'),
            route('jadwal-guru.cetak'),
            route('jadwal-ruang.index'),
            route('jadwal-ruang.cetak'),
            route('jadwal-ujian.index'),
            route('jadwal-ujian.create'),
            route('jadwal-ujian.cetak'),
            route('kegiatan-sekolah.index'),
            route('kegiatan-sekolah.create'),
            route('kegiatan-sekolah.cetak'),
            route('log-bentrok.index'),
            route('log-bentrok.cetak'),
        ];

        foreach ($urls as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
        }
    }
}

