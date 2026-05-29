<x-app-layout>
    <div class="mx-auto">
        <x-content-card title="Monitoring Surat Tugas" subtitle="Daftar surat tugas dan status pelaksanaannya.">
            @if(auth()->user()->role === 'admin')
                <x-slot name="action">
                    <a href="{{ route('surat-tugas.create') }}"
                        class="px-6 py-3 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all cursor-pointer flex items-center gap-2">
                        <x-lucide-plus class="w-4 h-4" />
                        Buat Surat Tugas
                    </a>
                </x-slot>
            @endif

            <x-data-table url="{{ route('surat-tugas.index') }}">
                <x-slot name="thead">
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nomor
                        Surat</th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Pegawai
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tujuan
                        Tugas</th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Lokasi
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal
                    </th>
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
                                <span class="text-xs text-gray-400 font-mono" x-text="item.pegawai_nip"></span>
                            </div>
                        </td>

                        {{-- Tujuan Tugas --}}
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" x-text="item.tujuan_tugas"></td>

                        {{-- Lokasi --}}
                        <td class="px-6 py-4 text-sm text-gray-700" x-text="item.lokasi_tugas ?? '-'"></td>

                        {{-- Tanggal Mulai – Selesai --}}
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <div class="flex flex-col gap-0.5">
                                <span
                                    x-text="item.tanggal_mulai_tugas ? new Date(item.tanggal_mulai_tugas).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'}) : '-'"></span>
                                <span class="text-xs text-gray-400">s.d. <span
                                        x-text="item.tanggal_selesai_tugas ? new Date(item.tanggal_selesai_tugas).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'}) : '-'"></span></span>
                            </div>
                        </td>

                        {{-- Status Persetujuan & Status Pelaksanaan --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1 items-start">
                                {{-- Status Persetujuan --}}
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ring-1 ring-inset"
                                    :class="{
                                        'bg-amber-50 text-amber-700 border-amber-200 ring-amber-500/20': item.status === 'diproses',
                                        'bg-green-50 text-green-700 border-green-200 ring-green-500/20': item.status === 'disetujui',
                                        'bg-red-50 text-red-700 border-red-200 ring-red-500/20': item.status === 'ditolak',
                                    }" x-text="item.status.charAt(0).toUpperCase() + item.status.slice(1)"></span>

                                {{-- Status Pelaksanaan --}}
                                <span x-show="item.status === 'disetujui'"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-medium border ring-1 ring-inset"
                                    :class="{
                                        'bg-blue-50 text-blue-700 border-blue-200 ring-blue-500/20': item.status_tugas === 'SEDANG_BERJALAN',
                                        'bg-green-50 text-green-700 border-green-200 ring-green-500/20': item.status_tugas === 'SELESAI',
                                        'bg-gray-50 text-gray-700 border-gray-200 ring-gray-500/20': item.status_tugas === 'BELUM_DIMULAI',
                                    }"
                                    x-text="item.status_tugas === 'SEDANG_BERJALAN' ? 'Sedang Berjalan' : item.status_tugas === 'SELESAI' ? 'Selesai' : item.status_tugas === 'BELUM_DIMULAI' ? 'Belum Dimulai' : '-'"></span>
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1">
                                {{-- Lihat --}}
                                <a :href="`/surat-tugas/${item.id}`"
                                    class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                                    title="Lihat Detail">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>

                                @if(auth()->user()->role === 'pegawai')
                                    {{-- Pegawai: Cetak --}}
                                    <a :href="`/surat-tugas/${item.id}?print=1`" target="_blank"
                                        x-show="item.status === 'disetujui'"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                                        title="Cetak">
                                        <x-lucide-printer class="w-4 h-4" />
                                    </a>
                                @endif

                                @if(auth()->user()->role === 'admin')
                                    {{-- Edit --}}
                                    <a :href="`/surat-tugas/${item.id}/edit`"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all duration-200"
                                        title="Edit">
                                        <x-lucide-pencil class="w-4 h-4" />
                                    </a>

                                    {{-- Cetak --}}
                                    <a :href="`/surat-tugas/${item.id}?print=1`" target="_blank"
                                        x-show="item.status === 'diproses' || item.status === 'disetujui'"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                                        title="Cetak">
                                        <x-lucide-printer class="w-4 h-4" />
                                    </a>

                                    {{-- Hapus --}}
                                    <button @click="$dispatch('open-confirm-modal', {
                                                                    title: 'Hapus Surat Tugas?',
                                                                    message: 'Tindakan ini tidak dapat dibatalkan.',
                                                                    action: `/surat-tugas/${item.id}`,
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
                                                                                    title: 'Setujui Surat Tugas?',
                                                                                    message: 'Surat tugas ini akan disetujui.',
                                                                                    action: `/surat-tugas/${item.id}/setujui`,
                                                                                    type: 'success',
                                                                                    confirmText: 'Setujui Surat',
                                                                                    method: 'POST'
                                                                                })"
                                        class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200"
                                        title="Setujui">
                                        <x-lucide-check-circle class="w-4 h-4" />
                                    </button>

                                    {{-- Pimpinan: Tolak --}}
                                    <button x-show="item.status === 'diproses'"
                                        @click="$dispatch('open-tolak-modal', item.id)"
                                        class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
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
