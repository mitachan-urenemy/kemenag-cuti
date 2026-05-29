<x-app-layout>
    <div class="mx-auto">
        <form method="post" action="{{ route('surat-cuti.store') }}">
            @csrf
            <x-content-card
                title="Pengajuan Cuti"
                subtitle="Isi form di bawah untuk mengajukan permohonan cuti."
            >
                <x-slot name="action">
                    <a href="{{ route('surat-cuti.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>
                </x-slot>

                <div class="space-y-6" x-data="{ jenisCuti: '{{ old('jenis_cuti', '') }}' }">
                    {{-- Error kuota --}}
                    @error('kuota')
                        <x-toast-notification type="error" :message="$message" />
                    @enderror

                    {{-- ── Section: Identitas Pegawai ── --}}
                    <div class="bg-blue-50/60 p-4 rounded-lg border border-blue-100">
                        <h3 class="text-sm font-medium text-blue-900 mb-4 flex items-center gap-2">
                            <x-lucide-user class="w-4 h-4 text-blue-600" />
                            Identitas Pegawai
                        </h3>

                        @if(auth()->user()->role === 'admin')
                            {{-- Admin: dropdown pilih pegawai --}}
                            <div x-data="{
                                pegawaiId: '{{ old('pegawai_id', '') }}',
                                pegawais: {{ $pegawais->map(fn($p) => ['id' => $p->id, 'nama_lengkap' => $p->nama_lengkap, 'nip' => $p->nip, 'jabatan' => $p->jabatan, 'unit_kerja' => $p->unit_kerja, 'pangkat_golongan' => $p->pangkat_golongan, 'status_kepegawaian' => $p->status_kepegawaian])->values()->toJson() }},
                                get selected() { return this.pegawais.find(p => p.id == this.pegawaiId) || null; }
                            }">
                                <div class="mb-4">
                                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Pilih Pegawai <span class="text-red-500">*</span></label>
                                    <select name="pegawai_id" x-model="pegawaiId" required
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih pegawai --</option>
                                        @foreach($pegawais as $p)
                                            <option value="{{ $p->id }}" {{ old('pegawai_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_lengkap }} - {{ $p->nip }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('pegawai_id')" />
                                </div>
                                {{-- Preview info pegawai terpilih --}}
                                <template x-if="selected">
                                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3 text-sm text-gray-700 pt-3 border-t border-blue-100">
                                        <div>
                                            <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Jabatan</span>
                                            <span class="font-semibold" x-text="selected.jabatan || '-'"></span>
                                        </div>
                                        <div>
                                            <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Unit Kerja</span>
                                            <span class="font-semibold" x-text="selected.unit_kerja || '-'"></span>
                                        </div>
                                        <div>
                                            <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Status Kepegawaian</span>
                                            <span class="font-semibold" x-text="selected.status_kepegawaian || '-'"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        @else
                            {{-- Pegawai: tampilkan info readonly --}}
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3 text-sm text-gray-700">
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Nama Lengkap</span>
                                    <span class="font-semibold">{{ $pegawai->nama_lengkap }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">NIP</span>
                                    <span class="font-semibold font-mono">{{ $pegawai->nip }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Status Kepegawaian</span>
                                    <span class="font-semibold">{{ $pegawai->status_kepegawaian }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Pangkat / Golongan</span>
                                    <span class="font-semibold">{{ $pegawai->pangkat_golongan ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Jabatan</span>
                                    <span class="font-semibold">{{ $pegawai->jabatan ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide mb-0.5">Unit Kerja</span>
                                    <span class="font-semibold">{{ $pegawai->unit_kerja ?? '-' }}</span>
                                </div>
                            </div>
                        @endif

                        @if($pimpinan)
                        <div class="mt-4 pt-4 border-t border-blue-100">
                            <span class="text-xs text-blue-600 font-semibold uppercase tracking-wide">Kepala Unit (Penandatangan)</span>
                            <div class="mt-2 text-sm text-gray-700">
                                <span class="font-semibold">{{ $pimpinan->nama_lengkap }}</span>
                                <span class="mx-2 text-gray-400">·</span>
                                <span class="text-gray-500">{{ $pimpinan->jabatan ?? '-' }}</span>
                            </div>
                        </div>
                        @else
                        <div class="mt-3 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-700">
                            ⚠ Belum ada pimpinan/kepala yang ditetapkan. Hubungi admin.
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
                                    :value="old('nomor_surat', $generatedNomorSurat)"
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
                                    :value="old('tanggal_surat', date('Y-m-d'))"
                                    required
                                />
                            </div>

                            <div class="col-span-1 md:col-span-3 flex items-center gap-4 py-1">
                                <div class="h-px flex-1 bg-gray-200"></div>
                                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Detail Cuti</span>
                                <div class="h-px flex-1 bg-gray-200"></div>
                            </div>

                            {{-- Jenis Cuti + Kuota Info --}}
                            <div class="col-span-1 md:col-span-3">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <x-forms.select
                                            title="Jenis Cuti"
                                            name="jenis_cuti"
                                            :options="[
                                                'tahunan'       => 'Cuti Tahunan',
                                                'sakit'         => 'Cuti Sakit',
                                                'melahirkan'    => 'Cuti Melahirkan',
                                                'alasan_penting'=> 'Cuti Alasan Penting',
                                                'besar'         => 'Cuti Besar',
                                            ]"
                                            placeholder="Pilih jenis cuti"
                                            :selected="old('jenis_cuti')"
                                            required
                                            x-model="jenisCuti"
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

                            {{-- Keterangan --}}
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
                                    placeholder="Contoh: 1. Kepala Kantor Wilayah... (pisahkan dengan baris baru)"
                                    rows="3"
                                    :value="old('tembusan')"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('surat-cuti.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                        <x-primary-button>Ajukan Cuti</x-primary-button>
                    </div>
                </x-slot>
            </x-content-card>
        </form>
    </div>
</x-app-layout>
