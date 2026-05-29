<x-app-layout>
    <div class="mx-auto max-w-4xl">
        <x-content-card title="Detail Surat Tugas" subtitle="Informasi lengkap mengenai surat tugas." class="no-print">
            <x-slot name="action">
                <div class="flex items-center gap-2">
                    @if(request('from') === 'riwayat')
                        <a href="{{ route('riwayat-surat') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            Kembali
                        </a>
                    @else
                        <a href="{{ route('surat-tugas.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            Kembali
                        </a>
                    @endif

                    @if(auth()->user()->role === 'admin' || $surat->status === 'disetujui')
                        <button onclick="printSurat()"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white uppercase transition-colors duration-200 bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700">
                            <x-lucide-printer class="w-4 h-4" />
                            Cetak Surat
                        </button>
                    @endif

                    @if(auth()->user()->role === 'pimpinan' && $surat->status === 'diproses')
                        <button @click="$dispatch('open-confirm-modal', {
                                        title: 'Setujui Surat Tugas?',
                                        message: 'Surat tugas ini akan disetujui.',
                                        action: `/surat-tugas/{{ $surat->id }}/setujui`,
                                        type: 'success',
                                        confirmText: 'Setujui Surat',
                                        method: 'POST'
                                    })"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white uppercase transition-colors duration-200 bg-green-600 border border-transparent rounded-lg hover:bg-green-700">
                            <x-lucide-check-circle class="w-4 h-4" />
                            Setujui
                        </button>

                        <button @click="$dispatch('open-tolak-modal', {{ $surat->id }})"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white uppercase transition-colors duration-200 bg-red-600 border border-transparent rounded-lg hover:bg-red-700">
                            <x-lucide-x-circle class="w-4 h-4" />
                            Tolak
                        </button>
                    @endif
                </div>
            </x-slot>

            <div class="space-y-6">
                {{-- Status & Nomor --}}
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Nomor Surat</p>
                        <p class="text-lg font-bold text-gray-900 mt-1 font-mono">{{ $surat->nomor_surat }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border ring-1 ring-inset {{
    $surat->status === 'draft' ? 'bg-gray-50 text-gray-700 border-gray-200 ring-gray-500/20' :
    ($surat->status === 'diajukan' ? 'bg-blue-50 text-blue-700 border-blue-200 ring-blue-500/20' :
        ($surat->status === 'diproses' ? 'bg-amber-50 text-amber-700 border-amber-200 ring-amber-500/20' :
            ($surat->status === 'disetujui' ? 'bg-green-50 text-green-700 border-green-200 ring-green-500/20' :
                'bg-red-50 text-red-700 border-red-200 ring-red-500/20')))
                        }}">
                            {{ ucfirst($surat->status) }}
                        </span>
                    </div>
                </div>

                {{-- Keterangan Ditolak --}}
                @if($surat->status === 'ditolak' && $surat->ditolak_alasan)
                    <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                        <div class="flex gap-3">
                            <x-lucide-alert-circle class="w-5 h-5 text-red-600 shrink-0" />
                            <div>
                                <h4 class="text-sm font-semibold text-red-800">Alasan Penolakan:</h4>
                                <p class="text-sm text-red-700 mt-1">{{ $surat->ditolak_alasan }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Data Pegawai Ditugaskan --}}
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">
                            Ditugaskan Kepada</h4>
                        <dl class="space-y-3 text-sm">
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="text-gray-500 font-medium col-span-1">Nama</dt>
                                <dd class="text-gray-900 font-semibold col-span-2">{{ $surat->pegawai->nama_lengkap }}
                                </dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="text-gray-500 font-medium col-span-1">NIP</dt>
                                <dd class="text-gray-900 font-mono col-span-2">{{ $surat->pegawai->nip }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="text-gray-500 font-medium col-span-1">Jabatan</dt>
                                <dd class="text-gray-900 col-span-2">{{ $surat->pegawai->jabatan ?? '-' }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="text-gray-500 font-medium col-span-1">Pangkat/Gol</dt>
                                <dd class="text-gray-900 col-span-2">{{ $surat->pegawai->pangkat_golongan ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Detail Tugas --}}
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">Detail
                            Penugasan</h4>
                        <dl class="space-y-3 text-sm">
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="text-gray-500 font-medium col-span-1">Maksud/Tujuan</dt>
                                <dd class="text-gray-900 font-semibold col-span-2">{{ $surat->tujuan_tugas }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="text-gray-500 font-medium col-span-1">Lokasi</dt>
                                <dd class="text-gray-900 col-span-2">{{ $surat->lokasi_tugas }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="text-gray-500 font-medium col-span-1">Tanggal</dt>
                                <dd class="text-gray-900 col-span-2">
                                    {{ \Carbon\Carbon::parse($surat->tanggal_mulai_tugas)->isoFormat('D MMMM Y') }}
                                    @if($surat->tanggal_mulai_tugas != $surat->tanggal_selesai_tugas)
                                        s.d.
                                        {{ \Carbon\Carbon::parse($surat->tanggal_selesai_tugas)->isoFormat('D MMMM Y') }}
                                    @endif
                                </dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="text-gray-500 font-medium col-span-1">Dasar Hukum</dt>
                                <dd class="text-gray-900 col-span-2">{{ $surat->dasar_hukum ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Print Script Frame --}}
            @if(auth()->user()->role === 'admin' || $surat->status === 'disetujui')
                <iframe id="print-frame" src=""
                    style="position: absolute; width: 0; height: 0; border: none; overflow: hidden;"></iframe>
                <script>
                    function printSurat() {
                        const frame = document.getElementById('print-frame');
                        frame.src = "{{ route('surat-tugas.show', ['surat_tugas' => $surat->id, 'print' => true]) }}";
                    }
                </script>
            @endif
        </x-content-card>

        {{-- ── Modal Tolak (pimpinan only) ── --}}
        @if(auth()->user()->role === 'pimpinan')
            <div x-data="{ tolakModal: false, tolakSuratId: null, tolakAlasan: '' }"
                @open-tolak-modal.window="tolakModal = true; tolakSuratId = $event.detail; tolakAlasan = '';">

                <div x-show="tolakModal" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
                    @keydown.escape.window="tolakModal = false">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4"
                        @click.outside="tolakModal = false">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                <x-lucide-x-circle class="w-5 h-5 text-red-600" />
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Tolak Surat Tugas</h3>
                                <p class="text-xs text-gray-500">Masukkan alasan penolakan.</p>
                            </div>
                        </div>

                        <form :action="`/surat-tugas/${tolakSuratId}/tolak`" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan <span
                                        class="text-red-500">*</span></label>
                                <textarea name="ditolak_alasan" x-model="tolakAlasan" rows="3" required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Tuliskan alasan penolakan..."></textarea>
                            </div>
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="tolakModal = false"
                                    class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                    Tolak Surat
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
