<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pegawai extends Model
{
    protected $fillable = [
        'user_id',
        'is_atasan',

        // Informasi Pribadi
        'nama_lengkap',
        'nip',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',

        // Kepegawaian
        'status_kepegawaian',
        'pangkat_golongan',
        'jabatan',
        'unit_kerja',
        'pendidikan',

        // Kontak
        'nomor_hp',
        'email',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function surat(): HasMany
    {
        return $this->hasMany(Surat::class, 'pegawai_id');
    }

    public function suratDisetujui(): HasMany
    {
        return $this->hasMany(Surat::class, 'approved_by');
    }

}
