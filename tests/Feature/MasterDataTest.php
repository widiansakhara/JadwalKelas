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

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_and_read_ruang(): void
    {
        $response = $this->post(route('ruang.store'), [
            'kode_ruang' => 'R-201',
            'nama_ruang' => 'Ruang Teori 201',
            'jenis'      => 'Teori',
            'kapasitas'  => 36,
        ]);

        $response->assertRedirect(route('ruang.index'));
        $this->assertDatabaseHas('ruang', ['kode_ruang' => 'R-201']);
    }

    public function test_cannot_delete_ruang_if_used_in_jadwal(): void
    {
        $ta = TahunAjaran::create(['tahun' => '2024/2025', 'semester' => 'Ganjil', 'status_aktif' => true]);
        $kelas = Kelas::create(['nama_kelas' => 'X-IPA-1', 'tingkat' => 'X', 'jurusan' => 'MIPA']);
        $guru = Guru::create(['nip' => '198001012005011001', 'nama' => 'Budi Santoso', 'email' => 'budi@sekolah.sch.id', 'no_hp' => '081234567891']);
        $mapel = MataPelajaran::create(['kode_mapel' => 'MAT01', 'nama_mapel' => 'Matematika', 'jam_per_minggu' => 4]);
        $ruang = Ruang::create(['kode_ruang' => 'R-101', 'nama_ruang' => 'Ruang 101', 'jenis' => 'Teori', 'kapasitas' => 36]);
        $jam = JamPelajaran::create(['jam_ke' => 1, 'jam_mulai' => '07:00:00', 'jam_selesai' => '07:45:00']);

        JadwalPelajaran::create([
            'id_tahun_ajaran' => $ta->id_tahun_ajaran,
            'id_kelas'        => $kelas->id_kelas,
            'id_mapel'        => $mapel->id_mapel,
            'id_guru'         => $guru->id_guru,
            'id_ruang'        => $ruang->id_ruang,
            'id_jam'          => $jam->id_jam,
            'hari'            => 'Senin',
        ]);

        $response = $this->delete(route('ruang.destroy', $ruang));
        $response->assertRedirect(route('ruang.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('ruang', ['id_ruang' => $ruang->id_ruang]);
    }

    public function test_can_create_and_read_guru(): void
    {
        $response = $this->post(route('guru.store'), [
            'nip'   => '198901012015011009',
            'nama'  => 'Guru Baru, S.Pd',
            'email' => 'gurubaru@sekolah.sch.id',
            'no_hp' => '081299998888',
        ]);

        $response->assertRedirect(route('guru.index'));
        $this->assertDatabaseHas('guru', ['nip' => '198901012015011009']);
    }

    public function test_cannot_delete_guru_if_assigned_to_jadwal(): void
    {
        $ta = TahunAjaran::create(['tahun' => '2024/2025', 'semester' => 'Ganjil', 'status_aktif' => true]);
        $kelas = Kelas::create(['nama_kelas' => 'X-IPA-1', 'tingkat' => 'X', 'jurusan' => 'MIPA']);
        $guru = Guru::create(['nip' => '198001012005011001', 'nama' => 'Budi Santoso', 'email' => 'budi@sekolah.sch.id', 'no_hp' => '081234567891']);
        $mapel = MataPelajaran::create(['kode_mapel' => 'MAT01', 'nama_mapel' => 'Matematika', 'jam_per_minggu' => 4]);
        $ruang = Ruang::create(['kode_ruang' => 'R-101', 'nama_ruang' => 'Ruang 101', 'jenis' => 'Teori', 'kapasitas' => 36]);
        $jam = JamPelajaran::create(['jam_ke' => 1, 'jam_mulai' => '07:00:00', 'jam_selesai' => '07:45:00']);

        JadwalPelajaran::create([
            'id_tahun_ajaran' => $ta->id_tahun_ajaran,
            'id_kelas'        => $kelas->id_kelas,
            'id_mapel'        => $mapel->id_mapel,
            'id_guru'         => $guru->id_guru,
            'id_ruang'        => $ruang->id_ruang,
            'id_jam'          => $jam->id_jam,
            'hari'            => 'Senin',
        ]);

        $response = $this->delete(route('guru.destroy', $guru));
        $response->assertRedirect(route('guru.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('guru', ['id_guru' => $guru->id_guru]);
    }
}

