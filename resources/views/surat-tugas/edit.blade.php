<x-app-layout>
    <div class="mx-auto">
        <form method="post" action="{{ route('surat-tugas.update', $surat_tugas) }}">
            @csrf
            @method('PATCH')
            <x-content-card
                title="Edit Surat Tugas"
                subtitle="Ubah detail di bawah ini untuk memperbarui surat tugas."
            >
                <x-slot name="action">
                    @if(request('from') === 'riwayat')
                        <a href="{{ route('riwayat-surat') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            Kembali
                        </a>
                    @else
                        <a href="{{ route('surat-tugas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
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
                        @php $pegawai = $surat_tugas->pegawai; @endphp
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

                        @php $pimpinan = $surat_tugas->approvedBy ?? \App\Models\Pegawai::where('is_atasan', true)->first(); @endphp
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

                    {{-- ── Section: Detail Surat & Tugas ── --}}
                    <div class="bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <x-lucide-briefcase class="w-4 h-4 text-gray-500" />
                            Detail Surat & Tugas
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            {{-- Nomor & Tanggal Surat --}}
                            <div class="col-span-1 md:col-span-2">
                                <x-forms.text
                                    title="Nomor Surat"
                                    name="nomor_surat"
                                    placeholder="Masukkan Nomor Surat"
                                    :value="old('nomor_surat', $surat_tugas->nomor_surat)"
                                    required
                                />
                            </div>
                            <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Surat"
                                    name="tanggal_surat"
                                    :value="old('tanggal_surat', $surat_tugas->tanggal_surat->format('Y-m-d'))"
                                    required
                                />
                            </div>

                            <div class="col-span-1 md:col-span-3 flex items-center gap-4 py-2">
                                <div class="h-px flex-1 bg-gray-200"></div>
                                <span class="text-[12px] font-bold text-gray-400 uppercase tracking-widest">Informasi Detail Tugas</span>
                                <div class="h-px flex-1 bg-gray-200"></div>
                            </div>

                            {{-- Perihal --}}
                            <div class="col-span-1 md:col-span-3">
                                <x-forms.text
                                    title="Perihal"
                                    name="perihal"
                                    placeholder="Contoh: Penyuluhan Moderasi Beragama"
                                    :value="old('perihal', $surat_tugas->perihal)"
                                    required
                                />
                            </div>

                            {{-- Dasar Hukum --}}
                            <div class="col-span-1 md:col-span-3">
                                <x-forms.textarea
                                    title="Dasar Hukum"
                                    name="dasar_hukum"
                                    placeholder="Contoh: DIPA Kementerian Agama Kab. Bener Meriah Tahun 2026"
                                    rows="3"
                                    :value="old('dasar_hukum', $surat_tugas->dasar_hukum)"
                                    required
                                />
                            </div>

                            {{-- Tujuan --}}
                            <div class="col-span-1 md:col-span-3">
                                <x-forms.textarea
                                    title="Tujuan Tugas"
                                    name="tujuan_tugas"
                                    placeholder="Contoh: Mengikuti Rapat Koordinasi"
                                    rows="3"
                                    :value="old('tujuan_tugas', $surat_tugas->tujuan_tugas)"
                                    required
                                />
                            </div>

                            {{-- Lokasi Tugas --}}
                            <div class="col-span-1">
                                <x-forms.text
                                    title="Lokasi Tugas"
                                    name="lokasi_tugas"
                                    placeholder="Contoh: Banda Aceh"
                                    :value="old('lokasi_tugas', $surat_tugas->lokasi_tugas)"
                                    required
                                />
                            </div>

                            {{-- Tanggal Mulai & Selesai --}}
                            <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Mulai Tugas"
                                    name="tanggal_mulai_tugas"
                                    :value="old('tanggal_mulai_tugas', $surat_tugas->tanggal_mulai_tugas->format('Y-m-d'))"
                                    required
                                />
                            </div>
                            <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Selesai Tugas"
                                    name="tanggal_selesai_tugas"
                                    :value="old('tanggal_selesai_tugas', $surat_tugas->tanggal_selesai_tugas->format('Y-m-d'))"
                                    required
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
                            <a href="{{ route('surat-tugas.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                        @endif
                        <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                    </div>
                </x-slot>
            </x-content-card>
        </form>
    </div>
</x-app-layout>
