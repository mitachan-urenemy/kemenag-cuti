<?php

namespace App\Http\Requests\Pegawai;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class StorePegawaiRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'username' => ['required', 'string', 'max:255', Rule::unique(User::class, 'username')],
      'password' => ['required', 'string', 'max:255', Rules\Password::min(6)->letters()->numbers()],

      'nama_lengkap' => ['required', 'string', 'max:255'],
      'nip' => ['required', 'string', 'max:50'],
      'jenis_kelamin' => ['required', 'string', 'in:laki,perempuan'],
      'tempat_lahir' => ['nullable', 'string', 'max:255'],
      'tanggal_lahir' => ['nullable', 'date'],

      'status_kepegawaian' => ['required', 'string', 'in:PNS,PPPK'],
      'pangkat_golongan' => ['nullable', 'string', 'max:255'],
      'jabatan' => ['nullable', 'string', 'max:255'],
      'unit_kerja' => ['nullable', 'string', 'max:255'],
      'pendidikan' => ['nullable', 'string', 'max:255'],
      'is_atasan' => ['required', 'integer', 'in:0,1'],

      'nomor_hp' => ['nullable', 'string', 'max:15'],
      'email' => ['nullable', 'string', 'max:255'],
    ];
  }
}
