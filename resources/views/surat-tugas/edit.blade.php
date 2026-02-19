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
                    <!-- Section: Informasi Pegawai & Surat -->
                    <div class="bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <x-lucide-user class="w-4 h-4 text-gray-500" />
                            Informasi Pegawai
                        </h3>
                         <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            {{-- Pegawai --}}
                            <x-forms.text
                                title="Nama Lengkap"
                                name="nama_lengkap_pegawai"
                                placeholder="Masukkan Nama Lengkap"
                                :value="$surat_tugas->nama_lengkap_pegawai ?? old('nama_lengkap_pegawai')"
                                required
                            />

                            <x-forms.text
                                title="Nomor Induk Pegawai (NIP)"
                                name="nip_pegawai"
                                placeholder="Masukkan Nomor Induk Pegawai"
                                :value="$surat_tugas->nip_pegawai ?? old('nip_pegawai')"
                                required
                            />

                            <x-forms.text
                                title="Pangkat/Golongan"
                                name="pangkat_golongan_pegawai"
                                placeholder="Masukkan Pangkat Golongan"
                                :value="$surat_tugas->pangkat_golongan_pegawai ?? old('pangkat_golongan_pegawai')"
                                required
                            />

                            <x-forms.text
                                title="Jabatan"
                                name="jabatan_pegawai"
                                placeholder="Masukkan Jabatan"
                                :value="$surat_tugas->jabatan_pegawai ?? old('jabatan_pegawai')"
                                required
                            />

                            <x-forms.text
                                title="Unit Kerja"
                                name="bidang_seksi_pegawai"
                                placeholder="Masukkan Unit Kerja"
                                :value="$surat_tugas->bidang_seksi_pegawai ?? old('bidang_seksi_pegawai')"
                                required
                            />

                            <x-forms.select
                                title="Status Pegawai"
                                name="status_pegawai"
                                :options="['PNS' => 'PNS', 'PPPK' => 'PPPK']"
                                :value="$surat_tugas->status_pegawai ?? old('status_pegawai')"
                                required
                            />

                            <div class="col-span-3 flex items-center gap-4 py-2">
                                <div class="h-px flex-1 bg-gray-200"></div>
                                <span class="text-[12px] font-bold text-gray-400 uppercase tracking-widest">Informasi Penandatangan</span>
                                <div class="h-px flex-1 bg-gray-200"></div>
                            </div>

                             {{-- Penandatangan --}}
                            <div class="col-span-3 grid grid-cols-1 gap-6 md:grid-cols-3">
                                <x-forms.text
                                    title="Nama Lengkap Kepala Unit"
                                    name="nama_lengkap_kepala_pegawai"
                                    placeholder="Masukkan Nama Lengkap Kepala Unit"
                                    :value="$surat_tugas->nama_lengkap_kepala_pegawai ?? old('nama_lengkap_kepala_pegawai')"
                                    required
                                />

                                <x-forms.text
                                    title="Nomor Induk Pegawai (NIP)"
                                    name="nip_kepala_pegawai"
                                    placeholder="Masukkan Nomor Induk Pegawai Kepala Unit"
                                    :value="$surat_tugas->nip_kepala_pegawai ?? old('nip_kepala_pegawai')"
                                    required
                                />

                                <x-forms.text
                                    title="Jabatan Kepala Unit"
                                    name="jabatan_kepala_pegawai"
                                    placeholder="Masukkan Jabatan Kepala Unit"
                                    :value="$surat_tugas->jabatan_kepala_pegawai ?? old('jabatan_kepala_pegawai')"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Section: Detail Surat & Tugas -->
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
                                    :value="$surat_tugas->nomor_surat ?? old('nomor_surat')"
                                    required
                                />
                            </div>
                            <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Surat"
                                    name="tanggal_surat"
                                    :value="$surat_tugas->tanggal_surat->format('Y-m-d') ?? old('tanggal_surat')"
                                    required
                                />
                            </div>

                            <div class="col-span-1 md:col-span-3 flex items-center gap-4 py-2">
                                <div class="h-px flex-1 bg-gray-200"></div>
                                <span class="text-[12px] font-bold text-gray-400 uppercase tracking-widest">Informasi Detail Tugas</span>
                                <div class="h-px flex-1 bg-gray-200"></div>
                            </div>

                            {{-- Dasar Hukum --}}
                            <div class="col-span-1 md:col-span-3">
                                <x-forms.textarea
                                    title="Dasar Hukum"
                                    name="dasar_hukum"
                                    placeholder="Contoh: Peraturan Menteri Agama No. 16 Tahun 2005"
                                    rows="3"
                                    :value="$surat_tugas->dasar_hukum ?? old('dasar_hukum')"
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
                                    :value="$surat_tugas->tujuan_tugas ?? old('tujuan_tugas')"
                                    required
                                />
                            </div>

                            {{-- Lokasi Tugas --}}
                            <div class="col-span-1">
                                <x-forms.text
                                    title="Lokasi Tugas"
                                    name="lokasi_tugas"
                                    placeholder="Contoh: Jakarta"
                                    :value="$surat_tugas->lokasi_tugas ?? old('lokasi_tugas')"
                                    required
                                />
                            </div>

                            {{-- Tanggal Mulai & Selesai --}}
                            <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Mulai Tugas"
                                    name="tanggal_mulai_tugas"
                                    :value="$surat_tugas->tanggal_mulai_tugas->format('Y-m-d') ?? old('tanggal_mulai_tugas')"
                                    required
                                />
                            </div>
                            <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Selesai Tugas"
                                    name="tanggal_selesai_tugas"
                                    :value="$surat_tugas->tanggal_selesai_tugas->format('Y-m-d') ?? old('tanggal_selesai_tugas')"
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
