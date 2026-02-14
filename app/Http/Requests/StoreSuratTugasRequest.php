<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuratTugasRequest extends FormRequest
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
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'penandatangan_id' => ['required', 'exists:pegawais,id'],
            'tanggal_surat' => ['required', 'date'],
            'dasar_hukum' => ['required', 'string', 'max:1000'],
            'tujuan_tugas' => ['required', 'string', 'max:255'],
            'lokasi_tugas' => ['required', 'string', 'max:255'],
            'tanggal_mulai_tugas' => ['required', 'date', 'after_or_equal:tanggal_surat'],
            'tanggal_selesai_tugas' => ['required', 'date', 'after_or_equal:tanggal_mulai_tugas'],
        ];
    }
}
