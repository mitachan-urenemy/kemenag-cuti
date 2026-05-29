<?php

namespace App\Http\Requests\SuratCuti;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSuratCutiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_surat'        => ['required', 'date'],
            'jenis_cuti'           => ['required', 'string', 'in:tahunan,sakit,melahirkan,alasan_penting,besar'],
            'tanggal_mulai_cuti'   => ['required', 'date'],
            'tanggal_selesai_cuti' => ['required', 'date', 'after_or_equal:tanggal_mulai_cuti'],
            'keterangan_cuti'      => ['nullable', 'string', 'max:2000'],
            'tembusan'             => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_selesai_cuti.after_or_equal' => 'Tanggal selesai cuti tidak boleh sebelum tanggal mulai.',
        ];
    }
}
