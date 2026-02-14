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
            'pegawai_id' => [
                'required',
                'exists:pegawais,id',
                function ($attribute, $value, $fail) use ($suratId) {
                    $activeLeaveQuery = Surat::where('jenis_surat', 'cuti')
                        ->where('pegawai_id', $value)
                        ->where('tanggal_selesai_cuti', '>=', now()->toDateString());

                    if ($suratId) {
                        $activeLeaveQuery->where('id', '!=', $suratId);
                    }

                    if ($activeLeaveQuery->exists()) {
                        $fail('Pegawai yang dipilih sudah memiliki surat cuti lain yang masih aktif.');
                    }
                },
            ],
            'jenis_cuti' => ['required', Rule::in(['tahunan', 'sakit', 'melahirkan'])],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_mulai_cuti' => ['required', 'date', 'after_or_equal:tanggal_surat'],
            'tanggal_selesai_cuti' => ['required', 'date', 'after_or_equal:tanggal_mulai_cuti'],
            'keterangan_cuti' => ['nullable', 'string', 'max:1000'],
            'penandatangan_id' => ['required', 'exists:pegawais,id'],
        ];
    }
}
