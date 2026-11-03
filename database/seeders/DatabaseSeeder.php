<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Ruang;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tahun Ajaran
        $ta1 = TahunAjaran::create([
            'tahun'        => '2024/2025',
            'semester'     => 'Ganjil',
            'status_aktif' => true,
        ]);

        $ta2 = TahunAjaran::create([
            'tahun'        => '2024/2025',
            'semester'     => 'Genap',
            'status_aktif' => false,
        ]);

        // 2. Jam Pelajaran
        $jamData = [
            ['jam_ke' => 1, 'jam_mulai' => '07:00:00', 'jam_selesai' => '07:45:00'],
            ['jam_ke' => 2, 'jam_mulai' => '07:45:00', 'jam_selesai' => '08:30:00'],
            ['jam_ke' => 3, 'jam_mulai' => '08:30:00', 'jam_selesai' => '09:15:00'],
            ['jam_ke' => 4, 'jam_mulai' => '09:30:00', 'jam_selesai' => '10:15:00'],
            ['jam_ke' => 5, 'jam_mulai' => '10:15:00', 'jam_selesai' => '11:00:00'],
            ['jam_ke' => 6, 'jam_mulai' => '11:00:00', 'jam_selesai' => '11:45:00'],
            ['jam_ke' => 7, 'jam_mulai' => '12:30:00', 'jam_selesai' => '13:15:00'],
            ['jam_ke' => 8, 'jam_mulai' => '13:15:00', 'jam_selesai' => '14:00:00'],
        ];
        $jams = [];
        foreach ($jamData as $jd) {
            $jams[$jd['jam_ke']] = JamPelajaran::create($jd);
        }

        // 3. Ruang
        $r101 = Ruang::create(['kode_ruang' => 'R-101', 'nama_ruang' => 'Ruang Teori X-IPA-1', 'jenis' => 'Teori', 'kapasitas' => 36]);
        $r102 = Ruang::create(['kode_ruang' => 'R-102', 'nama_ruang' => 'Ruang Teori X-IPA-2', 'jenis' => 'Teori', 'kapasitas' => 36]);
        $r103 = Ruang::create(['kode_ruang' => 'R-103', 'nama_ruang' => 'Ruang Teori XI-IPA-1', 'jenis' => 'Teori', 'kapasitas' => 36]);
        $labKom = Ruang::create(['kode_ruang' => 'LAB-KOM', 'nama_ruang' => 'Laboratorium Komputer', 'jenis' => 'Laboratorium', 'kapasitas' => 40]);
        $labIpa = Ruang::create(['kode_ruang' => 'LAB-IPA', 'nama_ruang' => 'Laboratorium IPA', 'jenis' => 'Laboratorium', 'kapasitas' => 36]);
        $aula = Ruang::create(['kode_ruang' => 'AULA', 'nama_ruang' => 'Aula Utama', 'jenis' => 'Aula', 'kapasitas' => 300]);

        // 4. Guru
        $guruA = Guru::create(['nip' => '198001012005011001', 'nama' => 'Drs. Budi Santoso, M.Pd', 'email' => 'budi.santoso@sekolah.sch.id', 'no_hp' => '081234567801']);
        $guruB = Guru::create(['nip' => '198203152006042002', 'nama' => 'Siti Rahmawati, S.Pd', 'email' => 'siti.rahmawati@sekolah.sch.id', 'no_hp' => '081234567802']);
        $guruC = Guru::create(['nip' => '198507202008011003', 'nama' => 'Ahmad Hidayat, M.Kom', 'email' => 'ahmad.hidayat@sekolah.sch.id', 'no_hp' => '081234567803']);
        $guruD = Guru::create(['nip' => '199011122014022004', 'nama' => 'Dewi Lestari, S.Si', 'email' => 'dewi.lestari@sekolah.sch.id', 'no_hp' => '081234567804']);
        $guruE = Guru::create(['nip' => '198805092011011005', 'nama' => 'Hendra Pratama, S.Pd', 'email' => 'hendra.pratama@sekolah.sch.id', 'no_hp' => '081234567805']);

        // 5. Mata Pelajaran
        $mat = MataPelajaran::create(['kode_mapel' => 'MAT01', 'nama_mapel' => 'Matematika Wajib', 'jam_per_minggu' => 4]);
        $fis = MataPelajaran::create(['kode_mapel' => 'FIS01', 'nama_mapel' => 'Fisika Dasar', 'jam_per_minggu' => 3]);
        $inf = MataPelajaran::create(['kode_mapel' => 'INF01', 'nama_mapel' => 'Informatika & Pemrograman', 'jam_per_minggu' => 3]);
        $bio = MataPelajaran::create(['kode_mapel' => 'BIO01', 'nama_mapel' => 'Biologi Terapan', 'jam_per_minggu' => 3]);
        $bin = MataPelajaran::create(['kode_mapel' => 'BIN01', 'nama_mapel' => 'Bahasa Indonesia', 'jam_per_minggu' => 4]);
        $big = MataPelajaran::create(['kode_mapel' => 'BIG01', 'nama_mapel' => 'Bahasa Inggris', 'jam_per_minggu' => 3]);

        // 6. Guru Mapel (Mapping Guru yang mengampu Mapel)
        GuruMapel::create(['id_guru' => $guruA->id_guru, 'id_mapel' => $mat->id_mapel]);
        GuruMapel::create(['id_guru' => $guruB->id_guru, 'id_mapel' => $bin->id_mapel]);
        GuruMapel::create(['id_guru' => $guruB->id_guru, 'id_mapel' => $big->id_mapel]);
        GuruMapel::create(['id_guru' => $guruC->id_guru, 'id_mapel' => $inf->id_mapel]);
        GuruMapel::create(['id_guru' => $guruD->id_guru, 'id_mapel' => $fis->id_mapel]);
        GuruMapel::create(['id_guru' => $guruD->id_guru, 'id_mapel' => $bio->id_mapel]);
        GuruMapel::create(['id_guru' => $guruE->id_guru, 'id_mapel' => $mat->id_mapel]);

        // 7. Kelas
        $k10ipa1 = Kelas::create(['nama_kelas' => 'X-IPA-1', 'tingkat' => 'X', 'jurusan' => 'MIPA']);
        $k10ipa2 = Kelas::create(['nama_kelas' => 'X-IPA-2', 'tingkat' => 'X', 'jurusan' => 'MIPA']);
        $k11ipa1 = Kelas::create(['nama_kelas' => 'XI-IPA-1', 'tingkat' => 'XI', 'jurusan' => 'MIPA']);
        $k12ipa1 = Kelas::create(['nama_kelas' => 'XII-IPA-1', 'tingkat' => 'XII', 'jurusan' => 'MIPA']);

        // 8. Contoh Jadwal Pelajaran yang valid
        // Senin X-IPA-1
        JadwalPelajaran::create([
            'id_tahun_ajaran' => $ta1->id_tahun_ajaran,
            'id_kelas'        => $k10ipa1->id_kelas,
            'id_mapel'        => $mat->id_mapel,
            'id_guru'         => $guruA->id_guru,
            'id_ruang'        => $r101->id_ruang,
            'id_jam'          => $jams[1]->id_jam,
            'hari'            => 'Senin',
        ]);
        JadwalPelajaran::create([
            'id_tahun_ajaran' => $ta1->id_tahun_ajaran,
            'id_kelas'        => $k10ipa1->id_kelas,
            'id_mapel'        => $mat->id_mapel,
            'id_guru'         => $guruA->id_guru,
            'id_ruang'        => $r101->id_ruang,
            'id_jam'          => $jams[2]->id_jam,
            'hari'            => 'Senin',
        ]);
        JadwalPelajaran::create([
            'id_tahun_ajaran' => $ta1->id_tahun_ajaran,
            'id_kelas'        => $k10ipa1->id_kelas,
            'id_mapel'        => $inf->id_mapel,
            'id_guru'         => $guruC->id_guru,
            'id_ruang'        => $labKom->id_ruang,
            'id_jam'          => $jams[3]->id_jam,
            'hari'            => 'Senin',
        ]);

        // Senin X-IPA-2 (Guru B, Ruang 102, Jam 1-2)
        JadwalPelajaran::create([
            'id_tahun_ajaran' => $ta1->id_tahun_ajaran,
            'id_kelas'        => $k10ipa2->id_kelas,
            'id_mapel'        => $bin->id_mapel,
            'id_guru'         => $guruB->id_guru,
            'id_ruang'        => $r102->id_ruang,
            'id_jam'          => $jams[1]->id_jam,
            'hari'            => 'Senin',
        ]);
        JadwalPelajaran::create([
            'id_tahun_ajaran' => $ta1->id_tahun_ajaran,
            'id_kelas'        => $k10ipa2->id_kelas,
            'id_mapel'        => $bin->id_mapel,
            'id_guru'         => $guruB->id_guru,
            'id_ruang'        => $r102->id_ruang,
            'id_jam'          => $jams[2]->id_jam,
            'hari'            => 'Senin',
        ]);
    }
}
