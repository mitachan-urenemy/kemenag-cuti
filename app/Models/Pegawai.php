<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

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
    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class);
    }

    /**
     * Get the surats signed by this Pegawai.
     * (Surat yang ditandatangani oleh pegawai ini)
     */
    public function suratsAsPenandatangan(): HasMany
    {
        return $this->hasMany(Surat::class, 'penandatangan_id');
    }

    /**
     * Calculate the duration of cuti and remaining cuti.
     * (Durasi cuti dan sisa cuti)
     */
    public function hitungCuti($tanggalMulai, $tanggalSelesai)
    {
        $mulai = Carbon::parse($tanggalMulai);
        $selesai = Carbon::parse($tanggalSelesai);
        $hariIni = Carbon::today();

        if ($selesai->lt($mulai)) {
            throw new \InvalidArgumentException('Tanggal selesai tidak boleh lebih kecil dari tanggal mulai');
        }

        // total durasi cuti (inklusif)
        $durasi = $mulai->diffInDays($selesai) + 1;

        // sisa cuti dari hari ini sampai tanggal selesai
        $sisa = $hariIni->gt($selesai)
            ? 0
            : $hariIni->diffInDays($selesai) + 1;

        return [
            'durasi' => $durasi,
            'sisa_cuti' => $sisa,
            'status' => match (true) {
                $hariIni->lt($mulai) => 'BELUM_DIMULAI',
                $hariIni->between($mulai, $selesai) => 'SEDANG_CUTI',
                default => 'SELESAI',
            },
        ];
    }
}
