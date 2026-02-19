<x-app-layout>
    <div class="mx-auto">
        <form method="post" action="{{ route('surat-cuti.store') }}">
            @csrf
            <x-content-card
                title="Buat Surat Cuti Baru"
                subtitle="Isi detail di bawah ini untuk membuat surat cuti baru."
            >
                <x-slot name="action">
                    <a href="{{ route('surat-cuti.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>
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
                                :value="old('nama_lengkap_pegawai')"
                                required
                            />

                            <x-forms.text
                                title="Nomor Induk Pegawai (NIP)"
                                name="nip_pegawai"
                                placeholder="Masukkan Nomor Induk Pegawai"
                                :value="old('nip_pegawai')"
                                required
                            />

                            <x-forms.text
                                title="Pangkat/Golongan"
                                name="pangkat_golongan_pegawai"
                                placeholder="Masukkan Pangkat Golongan"
                                :value="old('pangkat_golongan_pegawai')"
                                required
                            />

                            <x-forms.text
                                title="Jabatan"
                                name="jabatan_pegawai"
                                placeholder="Masukkan Jabatan"
                                :value="old('jabatan_pegawai')"
                                required
                            />

                            <x-forms.text
                                title="Unit Kerja"
                                name="bidang_seksi_pegawai"
                                placeholder="Masukkan Unit Kerja"
                                :value="old('bidang_seksi_pegawai')"
                                required
                            />

                            <x-forms.select
                                title="Status Pegawai"
                                name="status_pegawai"
                                :options="['PNS' => 'PNS', 'PPPK' => 'PPPK']"
                                :value="old('status_pegawai')"
                                required
                            />

                            <div class="col-span-1 md:col-span-3 flex items-center gap-4 py-2">
                                <div class="h-px flex-1 bg-gray-200"></div>
                                <span class="text-[12px] font-bold text-gray-400 uppercase tracking-widest">Informasi Penandatangan</span>
                                <div class="h-px flex-1 bg-gray-200"></div>
                            </div>

                            {{-- Penandatangan --}}
                            <x-forms.text
                                title="Nama Lengkap Kepala Unit"
                                name="nama_lengkap_kepala_pegawai"
                                placeholder="Masukkan Nama Lengkap Kepala Unit"
                                :value="old('nama_lengkap_kepala_pegawai')"
                                required
                            />

                            <x-forms.text
                                title="Nomor Induk Pegawai (NIP)"
                                name="nip_kepala_pegawai"
                                placeholder="Masukkan Nomor Induk Pegawai Kepala Unit"
                                :value="old('nip_kepala_pegawai')"
                                required
                            />

                            <x-forms.text
                                title="Jabatan Kepala Unit"
                                name="jabatan_kepala_pegawai"
                                placeholder="Masukkan Jabatan Kepala Unit"
                                :value="old('jabatan_kepala_pegawai')"
                                required
                            />
                        </div>
                    </div>

                    <!-- Section: Detail Cuti -->
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
                                    placeholder="Nomor Surat Hari Ini"
                                    :value="old('nomor_surat', $generatedNomorSurat)"
                                    required
                                />
                            </div>

                             {{-- Tanggal Surat --}}
                            <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Surat"
                                    name="tanggal_surat"
                                    :value="old('tanggal_surat', date('Y-m-d'))"
                                    required
                                />
                            </div>

                            <div class="col-span-1 md:col-span-3 flex items-center gap-4 py-2">
                                <div class="h-px flex-1 bg-gray-200"></div>
                                <span class="text-[12px] font-bold text-gray-400 uppercase tracking-widest">Detail Surat Cuti</span>
                                <div class="h-px flex-1 bg-gray-200"></div>
                            </div>

                             {{-- Jenis Cuti --}}
                            <div class="col-span-1">
                                <x-forms.select
                                    title="Jenis Cuti"
                                    name="jenis_cuti"
                                    :options="[
                                        'sakit' => 'Cuti Sakit',
                                        'melahirkan' => 'Cuti Melahirkan',
                                        'tahunan' => 'Cuti Tahunan',
                                        'alasan_penting' => 'Cuti Alasan Penting',
                                        'besar' => 'Cuti Besar',
                                    ]"
                                    placeholder="Pilih jenis cuti"
                                    :selected="old('jenis_cuti')"
                                    required
                                />
                            </div>

                             {{-- Tanggal Mulai & Selesai --}}
                             <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Mulai Cuti"
                                    name="tanggal_mulai_cuti"
                                    :value="old('tanggal_mulai_cuti')"
                                    required
                                />
                            </div>
                            <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Selesai Cuti"
                                    name="tanggal_selesai_cuti"
                                    :value="old('tanggal_selesai_cuti')"
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
                                    :value="old('keterangan_cuti')"
                                />
                            </div>

                             {{-- Tembusan --}}
                             <div class="col-span-1 md:col-span-3">
                                <x-forms.textarea
                                    title="Tembusan (Opsional)"
                                    name="tembusan"
                                    placeholder="Contoh: 1. Kepala Kantor Wilayah... (Pisahkan dengan baris baru)"
                                    rows="3"
                                    :value="old('tembusan')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <x-slot name="footer">
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('surat-cuti.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                        <x-primary-button>{{ __('Simpan & Lanjutkan') }}</x-primary-button>
                    </div>
                </x-slot>
            </x-content-card>
        </form>
    </div>
</x-app-layout>
