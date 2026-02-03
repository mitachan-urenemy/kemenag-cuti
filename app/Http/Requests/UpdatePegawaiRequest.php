<?php

namespace App\Http\Requests;

use App\Models\Pegawai;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePegawaiRequest extends FormRequest
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
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:25', Rule::unique(Pegawai::class)->ignore($this->pegawai->id)],
            'jabatan' => ['required', 'string', 'max:255'],
            'pangkat_golongan' => ['nullable', 'string', 'max:255'],
            'bidang_seksi' => ['nullable', 'string', 'max:255'],
        ];
    }
}
