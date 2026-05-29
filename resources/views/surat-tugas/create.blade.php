<x-app-layout>
    <div class="mx-auto">
        <form method="post" action="{{ route('surat-tugas.store') }}">
            @csrf
            <x-content-card title="Buat Surat Tugas"
                subtitle="Pilih pegawai dan isi detail tugas. Surat tugas langsung berstatus disetujui.">
                <x-slot name="action">
                    <a href="{{ route('surat-tugas.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>
                </x-slot>

                <div class="space-y-6" x-data="{
                    selectedPegawai: {{ json_encode(old('pegawai_ids', [])) }},
                    pegawaiList: {{ $pegawais->map(fn($p) => ['id' => $p->id, 'nama_lengkap' => $p->nama_lengkap, 'nip' => $p->nip, 'jabatan' => $p->jabatan, 'unit_kerja' => $p->unit_kerja])->values()->toJson() }},
                    toggle(id) {
                        const idx = this.selectedPegawai.indexOf(id);
                        if (idx === -1) this.selectedPegawai.push(id);
                        else this.selectedPegawai.splice(idx, 1);
                    },
                    isSelected(id) { return this.selectedPegawai.includes(id); },
                    getSelected() { return this.pegawaiList.filter(p => this.selectedPegawai.includes(p.id)); },
                    search: ''
                }">
                    {{-- ── Section: Pilih Pegawai ── --}}
                    <div class="bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <x-lucide-users class="w-4 h-4 text-gray-500" />
                            Pegawai yang Ditugaskan
                            <span class="ml-auto text-xs text-gray-400 font-normal">Pilih satu atau lebih</span>
                        </h3>

                        @error('pegawai_ids')
                            <p class="mb-3 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Input hidden untuk selected pegawai --}}
                        <template x-for="id in selectedPegawai" :key="id">
                            <input type="hidden" name="pegawai_ids[]" :value="id">
                        </template>

                        {{-- Search box --}}
                        <div class="mb-3 relative">
                            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                            <input type="text" x-model="search" placeholder="Cari pegawai..."
                                class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Daftar pegawai --}}
                        <div
                            class="max-h-64 overflow-y-auto rounded-lg border border-gray-200 divide-y divide-gray-100">
                            <template
                                x-for="pegawai in pegawaiList.filter(p => !search || p.nama_lengkap.toLowerCase().includes(search.toLowerCase()) || p.nip.includes(search))"
                                :key="pegawai.id">
                                <label
                                    class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-indigo-50/50 transition-colors"
                                    :class="isSelected(pegawai.id) ? 'bg-indigo-50' : ''">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                        :checked="isSelected(pegawai.id)" @change="toggle(pegawai.id)">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900" x-text="pegawai.nama_lengkap">
                                        </div>
                                        <div class="text-xs text-gray-500 font-mono" x-text="pegawai.nip"></div>
                                    </div>
                                    <div class="text-xs text-gray-400 text-right shrink-0"
                                        x-text="pegawai.jabatan ?? '-'"></div>
                                </label>
                            </template>
                        </div>

                        {{-- Preview yang terpilih --}}
                        <div x-show="selectedPegawai.length > 0" x-cloak class="mt-3">
                            <p class="text-xs text-gray-500 mb-2">
                                <span x-text="selectedPegawai.length"></span> pegawai dipilih:
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="p in getSelected()" :key="p.id">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
                                        <span x-text="p.nama_lengkap"></span>
                                        <button type="button" @click="toggle(p.id)"
                                            class="text-indigo-400 hover:text-indigo-600">
                                            <x-lucide-x class="w-3 h-3" />
                                        </button>
                                    </span>
                                </template>
                            </div>
                            <p class="mt-2 text-xs text-gray-400" x-show="selectedPegawai.length > 1" x-cloak>
                                ℹ Nomor surat akan diberi seri: 001/TUGAS/V/2026/1, 001/TUGAS/V/2026/2, dst.
                            </p>
                        </div>
                    </div>

                    {{-- ── Section: Info Pimpinan ── --}}
                    @if($pimpinan)
                        <div class="bg-blue-50/60 p-4 rounded-lg border border-blue-100">
                            <h3 class="text-sm font-medium text-blue-900 mb-2 flex items-center gap-2">
                                <x-lucide-stamp class="w-4 h-4 text-blue-600" />
                                Penandatangan
                            </h3>
                            <div class="text-sm text-gray-700">
                                <span class="font-semibold">{{ $pimpinan->nama_lengkap }}</span>
                                <span class="mx-2 text-gray-400">·</span>
                                <span class="text-gray-500">{{ $pimpinan->jabatan ?? '-' }}</span>
                                <span class="mx-2 text-gray-400">·</span>
                                <span class="font-mono text-xs text-gray-400">{{ $pimpinan->nip }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- ── Section: Detail Surat & Tugas ── --}}
                    <div class="bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <x-lucide-briefcase class="w-4 h-4 text-gray-500" />
                            Detail Surat & Tugas
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div class="col-span-1 md:col-span-2">
                                <x-forms.text title="Nomor Surat" name="nomor_surat" :value="old('nomor_surat', $generatedNomorSurat)" required />
                            </div>
                            <div>
                                <x-forms.date title="Tanggal Surat" name="tanggal_surat" :value="old('tanggal_surat', date('Y-m-d'))" required />
                            </div>

                            <div class="col-span-1 md:col-span-3">
                                <x-forms.text title="Perihal" name="perihal"
                                    placeholder="Contoh: Menghadiri Rapat Koordinasi" :value="old('perihal')"
                                    required />
                            </div>

                            <div class="col-span-1 md:col-span-3 flex items-center gap-4 py-1">
                                <div class="h-px flex-1 bg-gray-200"></div>
                                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Informasi
                                    Tugas</span>
                                <div class="h-px flex-1 bg-gray-200"></div>
                            </div>

                            <div class="col-span-1 md:col-span-3">
                                <x-forms.textarea title="Dasar Hukum" name="dasar_hukum"
                                    placeholder="Contoh: Peraturan Menteri Agama No. 6 Tahun 2022 ..." rows="3"
                                    :value="old('dasar_hukum')" required />
                            </div>
                            <div class="col-span-1 md:col-span-3">
                                <x-forms.textarea title="Tujuan Tugas" name="tujuan_tugas"
                                    placeholder="Contoh: Mengikuti Rapat Koordinasi Program Kerja" rows="3"
                                    :value="old('tujuan_tugas')" required />
                            </div>

                            <div>
                                <x-forms.text title="Lokasi Tugas" name="lokasi_tugas" placeholder="Contoh: Jakarta"
                                    :value="old('lokasi_tugas')" required />
                            </div>
                            <div>
                                <x-forms.date title="Tanggal Mulai Tugas" name="tanggal_mulai_tugas"
                                    :value="old('tanggal_mulai_tugas')" required />
                            </div>
                            <div>
                                <x-forms.date title="Tanggal Selesai Tugas" name="tanggal_selesai_tugas"
                                    :value="old('tanggal_selesai_tugas')" required />
                            </div>
                        </div>
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('surat-tugas.index') }}"
                            class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                        <x-primary-button>Buat Surat Tugas</x-primary-button>
                    </div>
                </x-slot>
            </x-content-card>
        </form>
    </div>
</x-app-layout>
