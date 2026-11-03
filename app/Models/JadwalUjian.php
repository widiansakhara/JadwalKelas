<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalUjian extends Model
{
    use HasFactory;

    protected $table = 'jadwal_ujian';
    protected $primaryKey = 'id_ujian';

    protected $fillable = [
        'id_tahun_ajaran',
        'id_kelas',
        'id_mapel',
        'id_ruang',
        'jenis_ujian',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mapel', 'id_mapel');
    }

    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class, 'id_ruang', 'id_ruang');
    }

    public function pengawas(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'jadwal_pengawas', 'id_ujian', 'id_guru');
    }

    public function jadwalPengawas(): HasMany
    {
        return $this->hasMany(JadwalPengawas::class, 'id_ujian', 'id_ujian');
    }

    public function getFormatJamAttribute(): string
    {
        return substr($this->jam_mulai, 0, 5) . ' - ' . substr($this->jam_selesai, 0, 5);
    }
}

