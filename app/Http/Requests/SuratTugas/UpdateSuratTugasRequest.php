<?php

namespace App\Http\Requests\SuratTugas;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSuratTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_surat' => ['required', 'string', 'max:100'],
            'tanggal_surat' => ['required', 'date'],
            'perihal' => ['required', 'string', 'max:255'],
            'dasar_hukum' => ['required', 'string', 'max:1000'],
            'tujuan_tugas' => ['required', 'string', 'max:1000'],
            'lokasi_tugas' => ['required', 'string', 'max:255'],
            'tanggal_mulai_tugas' => ['required', 'date'],
            'tanggal_selesai_tugas' => ['required', 'date', 'after_or_equal:tanggal_mulai_tugas'],
        ];
    }
}
