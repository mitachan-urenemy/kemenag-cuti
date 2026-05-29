<?php

namespace App\Http\Requests\SuratTugas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSuratTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_surat'          => ['required', 'string', 'max:100'],
            'tanggal_surat'        => ['required', 'date'],
            'perihal'              => ['required', 'string', 'max:255'],
            'dasar_hukum'          => ['required', 'string', 'max:2000'],
            'tujuan_tugas'         => ['required', 'string', 'max:500'],
            'lokasi_tugas'         => ['required', 'string', 'max:255'],
            'tanggal_mulai_tugas'  => ['required', 'date', 'after_or_equal:tanggal_surat'],
            'tanggal_selesai_tugas'=> ['required', 'date', 'after_or_equal:tanggal_mulai_tugas'],

            // Multi-pegawai (minimal 1)
            'pegawai_ids'          => ['required', 'array', 'min:1'],
            'pegawai_ids.*'        => ['required', 'integer', 'exists:pegawais,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'pegawai_ids.required' => 'Pilih setidaknya satu pegawai yang akan ditugaskan.',
            'pegawai_ids.min'      => 'Pilih setidaknya satu pegawai.',
        ];
    }
}
