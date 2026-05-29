<x-app-layout>
    <div class="mx-auto">
        <form method="post" action="{{ route('surat-cuti.update', $surat_cuti) }}">
            @csrf
            @method('PATCH')
            <x-content-card
                title="Edit Surat Cuti"
                subtitle="Ubah detail di bawah ini untuk memperbarui surat cuti."
            >
                <x-slot name="action">
                    @if(request('from') === 'riwayat')
                        <a href="{{ route('riwayat-surat') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            Kembali
                        </a>
                    @else
                        <a href="{{ route('surat-cuti.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            Kembali
                        </a>
                    @endif
                </x-slot>

                <div class="space-y-6">
                    {{-- ── Section: Identitas Pegawai (readonly) ── --}}
                    <div class="bg-blue-50/60 p-4 rounded-lg border border-blue-100">
                        <h3 class="text-sm font-medium text-blue-900 mb-4 flex items-center gap-2">
                            <x-lucide-user class="w-4 h-4 text-blue-600" />
                            Identitas Pegawai
                        </h3>
                        @php $pegawai = $surat_cuti->pegawai; @endphp
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3 text-sm text-gray-700">
                            <div>
                                <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Nama Lengkap</span>
                                <span class="font-semibold">{{ $pegawai?->nama_lengkap ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">NIP</span>
                                <span class="font-semibold font-mono">{{ $pegawai?->nip ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Status Kepegawaian</span>
                                <span class="font-semibold">{{ $pegawai?->status_kepegawaian ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Pangkat / Golongan</span>
                                <span class="font-semibold">{{ $pegawai?->pangkat_golongan ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Jabatan</span>
                                <span class="font-semibold">{{ $pegawai?->jabatan ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Unit Kerja</span>
                                <span class="font-semibold">{{ $pegawai?->unit_kerja ?? '-' }}</span>
                            </div>
                        </div>

                        @php $pimpinan = $surat_cuti->approvedBy ?? \App\Models\Pegawai::where('is_atasan', true)->first(); @endphp
                        @if($pimpinan)
                        <div class="mt-4 pt-4 border-t border-blue-100">
                            <span class="text-xs text-blue-600 font-semibold uppercase tracking-wide">Kepala Unit (Penandatangan)</span>
                            <div class="mt-2 text-sm text-gray-700">
                                <span class="font-semibold">{{ $pimpinan->nama_lengkap }}</span>
                                <span class="mx-2 text-gray-400">·</span>
                                <span class="text-gray-500">{{ $pimpinan->jabatan ?? '-' }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- ── Section: Detail Cuti ── --}}
                    <div class="bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <x-lucide-calendar class="w-4 h-4 text-gray-500" />
                            Detail Surat & Cuti
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            {{-- Nomor Surat --}}
                            <div class="col-span-1 md:col-span-2">
                                <x-forms.text
                                    title="Nomor Surat"
                                    name="nomor_surat"
                                    placeholder="Masukkan Nomor Surat"
                                    :value="old('nomor_surat', $surat_cuti->nomor_surat)"
                                    required
                                    readonly="{{ auth()->user()->role === 'pegawai' ? '1' : '' }}"
                                />
                                @if(auth()->user()->role === 'pegawai')
                                    <p class="mt-1 text-xs text-gray-500">Nomor resmi akan digenerate otomatis oleh Admin setelah diverifikasi.</p>
                                @endif
                            </div>

                            {{-- Tanggal Surat --}}
                            <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Surat"
                                    name="tanggal_surat"
                                    :value="old('tanggal_surat', $surat_cuti->tanggal_surat->format('Y-m-d'))"
                                    required
                                />
                            </div>

                            <div class="col-span-1 md:col-span-3 flex items-center gap-4 py-2">
                                <div class="h-px flex-1 bg-gray-200"></div>
                                <span class="text-[12px] font-bold text-gray-400 uppercase tracking-widest">Informasi Detail Cuti</span>
                                <div class="h-px flex-1 bg-gray-200"></div>
                            </div>

                            {{-- Jenis Cuti --}}
                            <div class="col-span-1">
                                <x-forms.select
                                    title="Jenis Cuti"
                                    name="jenis_cuti"
                                    :options="[
                                        'tahunan'        => 'Cuti Tahunan',
                                        'sakit'          => 'Cuti Sakit',
                                        'melahirkan'     => 'Cuti Melahirkan',
                                        'alasan_penting' => 'Cuti Alasan Penting',
                                        'besar'          => 'Cuti Besar',
                                    ]"
                                    placeholder="Pilih jenis cuti"
                                    :selected="old('jenis_cuti', $surat_cuti->jenis_cuti)"
                                    required
                                />
                            </div>

                            {{-- Tanggal Mulai & Selesai --}}
                            <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Mulai Cuti"
                                    name="tanggal_mulai_cuti"
                                    :value="old('tanggal_mulai_cuti', $surat_cuti->tanggal_mulai_cuti->format('Y-m-d'))"
                                    required
                                />
                            </div>
                            <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Selesai Cuti"
                                    name="tanggal_selesai_cuti"
                                    :value="old('tanggal_selesai_cuti', $surat_cuti->tanggal_selesai_cuti->format('Y-m-d'))"
                                    required
                                />
                            </div>

                            {{-- Keterangan Cuti --}}
                            <div class="col-span-1 md:col-span-3">
                                <x-forms.textarea
                                    title="Keterangan Cuti"
                                    name="keterangan_cuti"
                                    placeholder="Contoh: untuk keperluan keluarga."
                                    rows="3"
                                    :value="old('keterangan_cuti', $surat_cuti->keterangan_cuti)"
                                />
                            </div>

                            {{-- Tembusan --}}
                            <div class="col-span-1 md:col-span-3">
                                <x-forms.textarea
                                    title="Tembusan (Opsional)"
                                    name="tembusan"
                                    placeholder="Contoh: 1. Kepala Kantor Wilayah... (Pisahkan dengan baris baru)"
                                    rows="3"
                                    :value="old('tembusan', $surat_cuti->tembusan)"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="flex items-center justify-end gap-4">
                        @if(request('from') === 'riwayat')
                        <a href="{{ route('riwayat-surat') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                        @else
                        <a href="{{ route('surat-cuti.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                        @endif
                        <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                    </div>
                </x-slot>
            </x-content-card>
        </form>
    </div>
</x-app-layout>
