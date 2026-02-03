<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pegawai extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'nip',
        'pangkat_golongan',
        'jabatan',
        'bidang_seksi',
        'image_path',
        'is_kepala',
    ];

    /**
     * Get the user account associated with the Pegawai.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * The surats that belong to the Pegawai.
     * (Surat Cuti / Surat Tugas yang ditujukan untuk pegawai ini)
     */
    public function surats(): BelongsToMany
    {
        return $this->belongsToMany(Surat::class, 'pegawai_surat');
    }

    /**
     * Get the surats signed by this Pegawai.
     * (Surat yang ditandatangani oleh pegawai ini)
     */
    public function suratsAsPenandatangan(): HasMany
    {
        return $this->hasMany(Surat::class, 'penandatangan_id');
    }
}
