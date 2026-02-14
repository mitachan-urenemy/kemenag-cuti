<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="post" action="{{ route('surat-tugas.update', $surat_tugas) }}">
                @csrf
                @method('PATCH')
                <x-content-card
                    icon="briefcase"
                    title="Edit Surat Tugas"
                    subtitle="Ubah detail di bawah ini untuk memperbarui surat tugas."
                >
                    <x-slot name="action">
                        <a href="{{ route('surat-tugas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            Kembali
                        </a>
                    </x-slot>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        {{-- Pegawai --}}
                        <div class="col-span-2">
                            <x-forms.select-search
                                title="Pilih Pegawai"
                                name="pegawai_id"
                                placeholder="Cari dan pilih pegawai yang akan ditugaskan..."
                                required
                                :options="$pegawais"
                                :selected="$surat_tugas->pegawai_id ?? old('pegawai_id')"
                            />
                        </div>

                        {{-- Penandatangan --}}
                        <div class="col-span-2">
                           <x-forms.select-search
                                title="Pilih Penandatangan"
                                name="penandatangan_id"
                                placeholder="Pilih pejabat yang akan menandatangani surat..."
                                required
                                :options="$kepalaPegawai"
                                :selected="$surat_tugas->penandatangan_id ?? old('penandatangan_id')"
                            />
                        </div>

                        {{-- Tanggal Surat --}}
                        <x-forms.date
                            title="Tanggal Surat"
                            name="tanggal_surat"
                            :value="$surat_tugas->tanggal_surat->format('Y-m-d') ?? old('tanggal_surat')"
                            required
                        />

                        {{-- Dasar Hukum --}}
                        <div class="col-span-2">
                            <x-forms.textarea
                                title="Dasar Hukum"
                                name="dasar_hukum"
                                placeholder="Contoh: Peraturan Menteri Agama No. 16 Tahun 2005"
                                rows="3"
                                :value="$surat_tugas->dasar_hukum ?? old('dasar_hukum')"
                                required
                            />
                        </div>

                        {{-- Tujuan Tugas --}}
                        <div class="col-span-2">
                            <x-forms.text
                                title="Tujuan Tugas"
                                name="tujuan_tugas"
                                placeholder="Contoh: Mengikuti Rapat Koordinasi di Jakarta"
                                :value="$surat_tugas->tujuan_tugas ?? old('tujuan_tugas')"
                                required
                            />
                        </div>

                        {{-- Lokasi Tugas --}}
                        <x-forms.text
                            title="Lokasi Tugas"
                            name="lokasi_tugas"
                            placeholder="Contoh: Jakarta"
                            :value="$surat_tugas->lokasi_tugas ?? old('lokasi_tugas')"
                            required
                        />

                        {{-- Tanggal Mulai Tugas --}}
                        <x-forms.date
                            title="Tanggal Mulai Tugas"
                            name="tanggal_mulai_tugas"
                            :value="$surat_tugas->tanggal_mulai_tugas->format('Y-m-d') ?? old('tanggal_mulai_tugas')"
                            required
                        />

                        {{-- Tanggal Selesai Tugas --}}
                        <x-forms.date
                            title="Tanggal Selesai Tugas"
                            name="tanggal_selesai_tugas"
                            :value="$surat_tugas->tanggal_selesai_tugas->format('Y-m-d') ?? old('tanggal_selesai_tugas')"
                            required
                        />

                    </div>

                    <x-slot name="footer">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('surat-tugas.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                        </div>
                    </x-slot>
                </x-content-card>
            </form>
        </div>
    </div>
</x-app-layout>
