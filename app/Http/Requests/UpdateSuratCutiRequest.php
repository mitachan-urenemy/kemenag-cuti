<?php

namespace App\Http\Requests;

use App\Models\Surat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSuratCutiRequest extends FormRequest
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
        // Get the surat_cuti instance being updated from the route
        // This is necessary to exclude the current surat_cuti from the active leave check
        $suratId = $this->route('surat_cuti')->id ?? null;

        return [
            'nama_lengkap_pegawai' => ['required', 'string', 'max:255'],
            'nip_pegawai' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($suratId) {
                    $activeLeaveQuery = Surat::where('jenis_surat', 'cuti')
                        ->where('nip_pegawai', $value)
                        ->where('tanggal_selesai_cuti', '>=', now()->toDateString());

                    if ($suratId) {
                        $activeLeaveQuery->where('id', '!=', $suratId);
                    }

                    if ($activeLeaveQuery->exists()) {
                        $fail('Pegawai dengan NIP ini sudah memiliki surat cuti lain yang masih aktif.');
                    }
                },
            ],
            'pangkat_golongan_pegawai' => ['required', 'string', 'max:255'],
            'jabatan_pegawai' => ['required', 'string', 'max:255'],
            'bidang_seksi_pegawai' => ['required', 'string', 'max:255'],
            'status_pegawai' => ['required', 'string', 'in:PNS,PPPK'],

            'nama_lengkap_kepala_pegawai' => ['required', 'string', 'max:255'],
            'nip_kepala_pegawai' => ['required', 'string', 'max:50'],
            'jabatan_kepala_pegawai' => ['required', 'string', 'max:255'],

            'jenis_cuti' => ['required', Rule::in(['tahunan', 'sakit', 'melahirkan'])],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_mulai_cuti' => ['required', 'date', 'after_or_equal:tanggal_surat'],
            'tanggal_selesai_cuti' => ['required', 'date', 'after_or_equal:tanggal_mulai_cuti'],
            'keterangan_cuti' => ['nullable', 'string', 'max:1000'],
            'tembusan' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
