<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSuratTugasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // nomor_surat is not editable in update view

            'nama_lengkap_pegawai' => ['required', 'string', 'max:255'],
            'nip_pegawai' => ['required', 'string', 'max:50'],
            'pangkat_golongan_pegawai' => ['required', 'string', 'max:255'],
            'jabatan_pegawai' => ['required', 'string', 'max:255'],
            'bidang_seksi_pegawai' => ['required', 'string', 'max:255'],
            'status_pegawai' => ['required', 'string', 'in:PNS,PPPK'],

            'nama_lengkap_kepala_pegawai' => ['required', 'string', 'max:255'],
            'nip_kepala_pegawai' => ['required', 'string', 'max:50'],
            'jabatan_kepala_pegawai' => ['required', 'string', 'max:255'],

            'tanggal_surat' => ['required', 'date'],
            'dasar_hukum' => ['required', 'string', 'max:1000'],
            'tujuan_tugas' => ['required', 'string', 'max:255'],
            'lokasi_tugas' => ['required', 'string', 'max:255'],
            'tanggal_mulai_tugas' => ['required', 'date', 'after_or_equal:tanggal_surat'],
            'tanggal_selesai_tugas' => ['required', 'date', 'after_or_equal:tanggal_mulai_tugas'],
        ];
    }
}
