<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="post" action="{{ route('surat-cuti.update', $surat_cuti) }}">
                @csrf
                @method('PATCH')
                <x-content-card
                    icon="file-edit"
                    title="Edit Surat Cuti"
                    subtitle="Ubah detail di bawah ini untuk memperbarui surat cuti."
                >
                    <x-slot name="action">
                        <a href="{{ route('surat-cuti.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
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
                                placeholder="Cari dan pilih pegawai yang mengajukan cuti..."
                                required
                                :options="$pegawais"
                                :selected="$surat_cuti->pegawai_id ?? old('pegawai_id')"
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
                                :selected="$surat_cuti->penandatangan_id ?? old('penandatangan_id')"
                            />
                        </div>

                        {{-- Jenis Cuti --}}
                        <x-forms.select
                            title="Jenis Cuti"
                            name="jenis_cuti"
                            :options="[
                                'tahunan' => 'Cuti Tahunan',
                                'sakit' => 'Cuti Sakit',
                                'melahirkan' => 'Cuti Melahirkan',
                            ]"
                            placeholder="Pilih jenis cuti"
                            :selected="$surat_cuti->jenis_cuti ?? old('jenis_cuti')"
                            required
                        />

                        {{-- Tanggal Surat --}}
                        <x-forms.date
                            title="Tanggal Surat"
                            name="tanggal_surat"
                            :value="$surat_cuti->tanggal_surat->format('Y-m-d') ?? old('tanggal_surat')"
                            required
                        />

                        {{-- Tanggal Mulai Cuti --}}
                        <x-forms.date
                            title="Tanggal Mulai Cuti"
                            name="tanggal_mulai_cuti"
                            :value="$surat_cuti->tanggal_mulai_cuti->format('Y-m-d') ?? old('tanggal_mulai_cuti')"
                            required
                        />

                        {{-- Tanggal Selesai Cuti --}}
                        <x-forms.date
                            title="Tanggal Selesai Cuti"
                            name="tanggal_selesai_cuti"
                            :value="$surat_cuti->tanggal_selesai_cuti->format('Y-m-d') ?? old('tanggal_selesai_cuti')"
                            required
                        />

                        {{-- Keterangan Cuti --}}
                        <div class="col-span-2">
                            <x-forms.textarea
                                title="Keterangan Cuti"
                                name="keterangan_cuti"
                                placeholder="Contoh: untuk keperluan keluarga."
                                rows="3"
                                :value="$surat_cuti->keterangan_cuti ?? old('keterangan_cuti')"
                            />
                        </div>
                    </div>

                    <x-slot name="footer">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('surat-cuti.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                        </div>
                    </x-slot>
                </x-content-card>
            </form>
        </div>
    </div>
</x-app-layout>
