<x-app-layout>
    <div class="mx-auto" x-data="{
        tolakModal: false,
        tolakSuratId: null,
        tolakAlasan: '',
        verifModal: false,
        verifSuratId: null,
        nomorSurat: '{{ $nextNomorSurat ?? '' }}',
        openTolak(id) {
            this.tolakSuratId = id;
            this.tolakAlasan = '';
            this.tolakModal = true;
        },
        openVerif(id) {
            this.verifSuratId = id;
            this.verifModal = true;
        }
    }">
        <x-content-card
            title="{{ auth()->user()->role === 'pimpinan' ? 'Surat Cuti - Menunggu Persetujuan' : 'Daftar Surat Cuti' }}"
            subtitle="{{ auth()->user()->role === 'pimpinan' ? 'Surat cuti yang diteruskan admin untuk disetujui atau ditolak.' : 'Berikut adalah daftar surat cuti.' }}">
            @if(auth()->user()->role !== 'pimpinan')
                <x-slot name="action">
                    <a href="{{ route('surat-cuti.create') }}"
                        class="px-6 py-3 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all cursor-pointer flex items-center gap-2">
                        <x-lucide-plus class="w-4 h-4" />
                        {{ auth()->user()->role === 'admin' ? 'Ajukan Cuti' : 'Ajukan Cuti' }}
                    </a>
                </x-slot>
            @endif

            <x-data-table url="{{ route('surat-cuti.index') }}">
                <x-slot name="thead">
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nomor
                        Surat</th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Pegawai
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jenis
                        Cuti</th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Hari</th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status
                    </th>
                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                </x-slot>

                <x-slot name="tbody">
                    <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                        {{-- Nomor Surat --}}
                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                            <span class="font-mono text-sm font-semibold text-indigo-600 tracking-tight"
                                x-text="item.nomor_surat"></span>
                        </td>

                        {{-- Pegawai --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900" x-text="item.pegawai_nama"></span>
                                <span class="text-xs text-gray-400" x-text="item.pegawai_nip"></span>
                            </div>
                        </td>

                        {{-- Jenis Cuti --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ring-1 ring-inset capitalize"
                                :class="{
                                    'bg-green-50 text-green-700 border-green-100 ring-green-600/20': item.jenis_cuti === 'tahunan',
                                    'bg-yellow-50 text-yellow-700 border-yellow-100 ring-yellow-600/20': item.jenis_cuti === 'sakit',
                                    'bg-pink-50 text-pink-700 border-pink-100 ring-pink-600/20': item.jenis_cuti === 'melahirkan',
                                    'bg-orange-50 text-orange-700 border-orange-100 ring-orange-600/20': item.jenis_cuti === 'alasan_penting',
                                    'bg-purple-50 text-purple-700 border-purple-100 ring-purple-600/20': item.jenis_cuti === 'besar',
                                }" x-text="(item.jenis_cuti || '').replace('_', ' ')"></span>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <x-lucide-calendar class="w-3.5 h-3.5 text-gray-400" />
                                <span
                                    x-text="item.tanggal_mulai_cuti ? new Date(item.tanggal_mulai_cuti).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'}) : '-'"></span>
                            </div>
                        </td>

                        {{-- Jumlah Hari --}}
                        <td class="px-6 py-4 text-sm text-center text-gray-700 font-semibold">
                            <span x-text="item.jumlah_hari ? item.jumlah_hari + ' hr' : '-'"></span>
                        </td>

                        {{-- Status Badge --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ring-1 ring-inset"
                                :class="{
                                    'bg-gray-50 text-gray-700 border-gray-200 ring-gray-500/20': item.status === 'draft',
                                    'bg-blue-50 text-blue-700 border-blue-200 ring-blue-500/20': item.status === 'diajukan',
                                    'bg-amber-50 text-amber-700 border-amber-200 ring-amber-500/20': item.status === 'diproses',
                                    'bg-green-50 text-green-700 border-green-200 ring-green-500/20': item.status === 'disetujui',
                                    'bg-red-50 text-red-700 border-red-200 ring-red-500/20': item.status === 'ditolak',
                                }" x-text="item.status"></span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1">
                                {{-- Lihat --}}
                                <a :href="`/surat-cuti/${item.id}`"
                                    class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                                    title="Lihat Detail">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>

                                @if(auth()->user()->role === 'pegawai')
                                    {{-- Pegawai: Edit (hanya draft/diajukan) --}}
                                    <a :href="`/surat-cuti/${item.id}/edit`"
                                        x-show="item.status === 'draft' || item.status === 'diajukan'"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all duration-200"
                                        title="Edit">
                                        <x-lucide-pencil class="w-4 h-4" />
                                    </a>

                                    {{-- Pegawai: Cetak (hanya disetujui) --}}
                                    <a :href="`/surat-cuti/${item.id}?print=1`" x-show="item.status === 'disetujui'"
                                        target="_blank"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                                        title="Cetak">
                                        <x-lucide-printer class="w-4 h-4" />
                                    </a>
                                @endif

                                @if(auth()->user()->role === 'admin')
                                    {{-- Admin: Verifikasi (hanya status diajukan) --}}
                                    <button x-show="item.status === 'diajukan'" @click="openVerif(item.id)"
                                        class="inline-flex items-center justify-center w-8 h-8 text-amber-500 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-all duration-200"
                                        title="Teruskan ke Pimpinan">
                                        <x-lucide-send class="w-4 h-4" />
                                    </button>

                                    {{-- Admin: Edit (hanya draft/diajukan) --}}
                                    <a :href="`/surat-cuti/${item.id}/edit`"
                                        x-show="item.status === 'draft' || item.status === 'diajukan'"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all duration-200"
                                        title="Edit">
                                        <x-lucide-pencil class="w-4 h-4" />
                                    </a>

                                    {{-- Admin: Cetak (hanya disetujui) --}}
                                    <a :href="`/surat-cuti/${item.id}?print=1`" x-show="item.status === 'disetujui'"
                                        target="_blank"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                                        title="Cetak">
                                        <x-lucide-printer class="w-4 h-4" />
                                    </a>

                                    {{-- Admin: Hapus --}}
                                    <button @click="$dispatch('open-confirm-modal', {
                                                                                title: 'Hapus Surat Cuti?',
                                                                                message: 'Tindakan ini tidak dapat dibatalkan.',
                                                                                action: `/surat-cuti/${item.id}`,
                                                                                method: 'DELETE'
                                                                            })"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                        title="Hapus">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                @endif

                                @if(auth()->user()->role === 'pimpinan')
                                    {{-- Pimpinan: Setujui --}}
                                    <button x-show="item.status === 'diproses'" @click="$dispatch('open-confirm-modal', {
                                                                                title: 'Setujui Surat Cuti?',
                                                                                message: 'Surat cuti akan disetujui dan kuota cuti pegawai akan dikurangi.',
                                                                                action: `/surat-cuti/${item.id}/setujui`,
                                                                                type: 'success',
                                                                                confirmText: 'Setujui Surat',
                                                                                method: 'POST'
                                                                            })"
                                        class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200"
                                        title="Setujui">
                                        <x-lucide-check-circle class="w-4 h-4" />
                                    </button>

                                    {{-- Pimpinan: Tolak (buka modal alasan) --}}
                                    <button x-show="item.status === 'diproses'" @click="openTolak(item.id)"
                                        class="inline-flex items-center justify-center w-8 h-8 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200"
                                        title="Tolak">
                                        <x-lucide-x-circle class="w-4 h-4" />
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                </x-slot>
            </x-data-table>
        </x-content-card>

        {{-- ── Modal Verifikasi (Admin Only) ── --}}
        @if(auth()->user()->role === 'admin')
            <div x-show="verifModal" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
                @keydown.escape.window="verifModal = false">
                <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4" @click.outside="verifModal = false">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <x-lucide-send class="w-5 h-5 text-blue-600" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Teruskan ke Pimpinan</h3>
                            <p class="text-xs text-gray-500">Tetapkan nomor surat resmi sebelum meneruskan.</p>
                        </div>
                    </div>

                    <form :action="`/surat-cuti/${verifSuratId}/verifikasi`" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat Resmi <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nomor_surat" x-model="nomorSurat" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nomor surat">
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="verifModal = false"
                                class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                Teruskan Surat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- ── Modal Tolak (pimpinan only) ── --}}
        @if(auth()->user()->role === 'pimpinan')
            <div x-show="tolakModal" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
                @keydown.escape.window="tolakModal = false">
                <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4" @click.outside="tolakModal = false">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <x-lucide-x-circle class="w-5 h-5 text-red-600" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Tolak Surat Cuti</h3>
                            <p class="text-xs text-gray-500">Masukkan alasan penolakan.</p>
                        </div>
                    </div>

                    <form :action="`/surat-cuti/${tolakSuratId}/tolak`" method="POST">
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
                            <button type="submit" :disabled="!tolakAlasan.trim()"
                                class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                Tolak Surat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
