<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Master Guru
        Schema::create('guru', function (Blueprint $table) {
            $table->id('id_guru');
            $table->string('nip', 20)->unique();
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->string('no_hp', 20);
            $table->timestamps();
        });

        // 2. Master Mata Pelajaran
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id('id_mapel');
            $table->string('kode_mapel', 10)->unique();
            $table->string('nama_mapel', 100);
            $table->integer('jam_per_minggu');
            $table->timestamps();
        });

        // 3. Relasi Guru - Mata Pelajaran yang diampu
        Schema::create('guru_mapel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_guru')->constrained('guru', 'id_guru')->cascadeOnDelete();
            $table->foreignId('id_mapel')->constrained('mata_pelajaran', 'id_mapel')->cascadeOnDelete();
            $table->unique(['id_guru', 'id_mapel']);
            $table->timestamps();
        });

        // 4. Master Kelas
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('nama_kelas', 20);
            $table->string('tingkat', 5);
            $table->string('jurusan', 30);
            $table->timestamps();
        });

        // 5. Master Ruang
        Schema::create('ruang', function (Blueprint $table) {
            $table->id('id_ruang');
            $table->string('kode_ruang', 10)->unique();
            $table->string('nama_ruang', 50);
            $table->string('jenis', 30);
            $table->integer('kapasitas');
            $table->timestamps();
        });

        // 6. Master Jam Pelajaran
        Schema::create('jam_pelajaran', function (Blueprint $table) {
            $table->id('id_jam');
            $table->integer('jam_ke');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });

        // 7. Master Tahun Ajaran
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id('id_tahun_ajaran');
            $table->string('tahun', 9);
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        // 8. Jadwal Pelajaran (Kelas)
        Schema::create('jadwal_pelajaran', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->foreignId('id_tahun_ajaran')->constrained('tahun_ajaran', 'id_tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('id_kelas')->constrained('kelas', 'id_kelas')->cascadeOnDelete();
            $table->foreignId('id_mapel')->constrained('mata_pelajaran', 'id_mapel')->cascadeOnDelete();
            $table->foreignId('id_guru')->constrained('guru', 'id_guru')->cascadeOnDelete();
            $table->foreignId('id_ruang')->constrained('ruang', 'id_ruang')->cascadeOnDelete();
            $table->foreignId('id_jam')->constrained('jam_pelajaran', 'id_jam')->cascadeOnDelete();
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->timestamps();

            // Constraint unik sesuai ERD
            $table->unique(['id_tahun_ajaran', 'hari', 'id_jam', 'id_guru'], 'unique_jadwal_guru');
            $table->unique(['id_tahun_ajaran', 'hari', 'id_jam', 'id_kelas'], 'unique_jadwal_kelas');
            $table->unique(['id_tahun_ajaran', 'hari', 'id_jam', 'id_ruang'], 'unique_jadwal_ruang');
        });

        // 9. Jadwal Ujian
        Schema::create('jadwal_ujian', function (Blueprint $table) {
            $table->id('id_ujian');
            $table->foreignId('id_tahun_ajaran')->constrained('tahun_ajaran', 'id_tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('id_kelas')->constrained('kelas', 'id_kelas')->cascadeOnDelete();
            $table->foreignId('id_mapel')->constrained('mata_pelajaran', 'id_mapel')->cascadeOnDelete();
            $table->foreignId('id_ruang')->constrained('ruang', 'id_ruang')->cascadeOnDelete();
            $table->enum('jenis_ujian', ['UTS', 'UAS', 'Ulangan Harian', 'Try Out']);
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });

        // 10. Jadwal Pengawas Ujian
        Schema::create('jadwal_pengawas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_ujian')->constrained('jadwal_ujian', 'id_ujian')->cascadeOnDelete();
            $table->foreignId('id_guru')->constrained('guru', 'id_guru')->cascadeOnDelete();
            $table->unique(['id_ujian', 'id_guru']);
            $table->timestamps();
        });

        // 11. Kegiatan Sekolah
        Schema::create('kegiatan_sekolah', function (Blueprint $table) {
            $table->id('id_kegiatan');
            $table->string('nama_kegiatan', 100);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->foreignId('id_ruang')->nullable()->constrained('ruang', 'id_ruang')->nullOnDelete();
            $table->foreignId('id_penanggung_jawab')->constrained('guru', 'id_guru')->cascadeOnDelete();
            $table->timestamps();
        });

        // 12. Log Bentrok (Hasil deteksi otomatis)
        Schema::create('log_bentrok', function (Blueprint $table) {
            $table->id('id_log');
            $table->string('jenis_bentrok', 50);
            $table->string('jenis_jadwal', 50);
            $table->unsignedBigInteger('id_referensi')->nullable();
            $table->text('deskripsi');
            $table->dateTime('waktu_deteksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_bentrok');
        Schema::dropIfExists('kegiatan_sekolah');
        Schema::dropIfExists('jadwal_pengawas');
        Schema::dropIfExists('jadwal_ujian');
        Schema::dropIfExists('jadwal_pelajaran');
        Schema::dropIfExists('tahun_ajaran');
        Schema::dropIfExists('jam_pelajaran');
        Schema::dropIfExists('ruang');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('guru_mapel');
        Schema::dropIfExists('mata_pelajaran');
        Schema::dropIfExists('guru');
    }
};

