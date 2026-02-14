<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="post" action="{{ route('surat-tugas.store') }}">
                @csrf
                <x-content-card
                    icon="briefcase"
                    title="Buat Surat Tugas Baru"
                    subtitle="Isi detail di bawah ini untuk membuat surat tugas baru."
                >
                    <x-slot name="action">
                        <a href="{{ route('surat-tugas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            Kembali
                        </a>
                    </x-slot>

                    <div class="space-y-6">
                        <!-- Section: Informasi Pegawai & Surat -->
                        <div class="bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                            <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center gap-2">
                                <x-lucide-user class="w-4 h-4 text-gray-500" />
                                Informasi Pegawai & Surat
                            </h3>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                {{-- Pegawai & Penandatangan --}}
                                <div class="col-span-1">
                                    <x-forms.select-search
                                        title="Pilih Pegawai"
                                        name="pegawai_id"
                                        placeholder="Cari pegawai..."
                                        required
                                        :options="$pegawais"
                                        :selected="old('pegawai_id')"
                                    />
                                </div>
                                <div class="col-span-1">
                                    <x-forms.select-search
                                        title="Pilih Penandatangan"
                                        name="penandatangan_id"
                                        placeholder="Pilih pejabat..."
                                        required
                                        :options="$kepalaPegawai"
                                        :selected="old('penandatangan_id')"
                                    />
                                </div>

                                {{-- Nomor & Tanggal Surat --}}
                                <div class="col-span-1">
                                    <x-forms.text
                                        title="Nomor Surat"
                                        name="nomor_surat"
                                        placeholder="Nomor Surat"
                                        :value="old('nomor_surat', $generatedNomorSurat)"
                                        required
                                    />
                                </div>
                                <div class="col-span-1">
                                    <x-forms.date
                                        title="Tanggal Surat"
                                        name="tanggal_surat"
                                        :value="old('tanggal_surat', date('Y-m-d'))"
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Detail Tugas -->
                        <div class="bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                            <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center gap-2">
                                <x-lucide-briefcase class="w-4 h-4 text-gray-500" />
                                Detail Tugas
                            </h3>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                {{-- Dasar Hukum --}}
                                <div class="col-span-2">
                                    <x-forms.textarea
                                        title="Dasar Hukum"
                                        name="dasar_hukum"
                                        placeholder="Contoh: Peraturan Menteri Agama No. 16 Tahun 2005"
                                        rows="2"
                                        :value="old('dasar_hukum')"
                                        required
                                    />
                                </div>

                                {{-- Tujuan & Lokasi --}}
                                <div class="col-span-1">
                                    <x-forms.text
                                        title="Tujuan Tugas"
                                        name="tujuan_tugas"
                                        placeholder="Contoh: Mengikuti Rapat Koordinasi"
                                        :value="old('tujuan_tugas')"
                                        required
                                    />
                                </div>
                                <div class="col-span-1">
                                    <x-forms.text
                                        title="Lokasi Tugas"
                                        name="lokasi_tugas"
                                        placeholder="Contoh: Jakarta"
                                        :value="old('lokasi_tugas')"
                                        required
                                    />
                                </div>

                                {{-- Tanggal Mulai & Selesai --}}
                                <div class="col-span-1">
                                    <x-forms.date
                                        title="Tanggal Mulai Tugas"
                                        name="tanggal_mulai_tugas"
                                        :value="old('tanggal_mulai_tugas')"
                                        required
                                    />
                                </div>
                                <div class="col-span-1">
                                    <x-forms.date
                                        title="Tanggal Selesai Tugas"
                                        name="tanggal_selesai_tugas"
                                        :value="old('tanggal_selesai_tugas')"
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-slot name="footer">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('surat-tugas.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                            <x-primary-button>{{ __('Simpan & Lanjutkan') }}</x-primary-button>
                        </div>
                    </x-slot>
                </x-content-card>
            </form>
        </div>
    </div>
</x-app-layout>
