<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Surat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Common fields
        'nomor_surat',
        'jenis_surat',
        'tanggal_surat',
        'perihal',
        'kop_surat_id',
        'penandatangan_id',
        'created_by_user_id',
        'file_path',

        // Cuti-specific fields
        'jenis_cuti',
        'tanggal_mulai_cuti',
        'tanggal_selesai_cuti',
        'keterangan_cuti',

        // Tugas-specific fields
        'dasar_hukum',
        'tujuan_tugas',
        'lokasi_tugas',
        'tanggal_mulai_tugas',
        'tanggal_selesai_tugas',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_mulai_cuti' => 'date',
        'tanggal_selesai_cuti' => 'date',
        'tanggal_mulai_tugas' => 'date',
        'tanggal_selesai_tugas' => 'date',
    ];

    /**
     * The pegawais that belong to the Surat.
     * (Pegawai yang dituju oleh surat ini)
     */
    public function pegawais(): BelongsToMany
    {
        return $this->belongsToMany(Pegawai::class, 'pegawai_surat');
    }

    /**
     * Get the kop_surat that owns the Surat.
     */
    public function kopSurat(): BelongsTo
    {
        return $this->belongsTo(KopSurat::class, 'kop_surat_id');
    }

    /**
     * Get the penandatangan (signer) of the Surat.
     */
    public function penandatangan(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'penandatangan_id');
    }

    /**
     * Get the user who created the Surat.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
