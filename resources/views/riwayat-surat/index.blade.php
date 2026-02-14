<x-app-layout>
    <div class="mx-auto">
        <x-content-card
            icon="history"
            title="Riwayat Surat"
            subtitle="Daftar semua surat yang telah dibuat dalam sistem."
            :padding="false"
            >
            <x-slot name="action">
                <x-modal-surat>
                    <x-slot name="trigger">
                        <span class="px-6 py-3 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all cursor-pointer flex items-center gap-2">
                            <x-lucide-plus class="w-5 h-5" />
                            Buat Surat
                        </span>
                    </x-slot>
                </x-modal-surat>
            </x-slot>

            <x-data-table url="{{ route('riwayat-surat') }}" :has-filters="true">
                <x-slot name="filters">
                    <!-- Filter Jenis Surat -->
                    <div>
                        <label for="filter_jenis_surat" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            <x-lucide-tag class="w-4 h-4 inline mr-1" />
                            Jenis Surat
                        </label>
                        <select
                            id="filter_jenis_surat"
                            x-model="filters.jenis_surat"
                            @change="fetchData()"
                            class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm transition-colors duration-200"
                        >
                            <option value="all">Semua Jenis Surat</option>
                            <option value="cuti">Surat Cuti</option>
                            <option value="tugas">Surat Tugas</option>
                        </select>
                    </div>

                    <!-- Filter Jenis Cuti -->
                    <div>
                        <label for="filter_jenis_cuti" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            <x-lucide-calendar class="w-4 h-4 inline mr-1" />
                            Jenis Cuti
                        </label>
                        <select
                            id="filter_jenis_cuti"
                            x-model="filters.jenis_cuti"
                            @change="fetchData()"
                            :disabled="isJenisCutiDisabled"
                            class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm transition-colors duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed"
                        >
                            <option value="all">Semua Jenis Cuti</option>
                            <option value="tahunan">Cuti Tahunan</option>
                            <option value="sakit">Cuti Sakit</option>
                            <option value="melahirkan">Cuti Melahirkan</option>
                            <option value="alasan_penting">Cuti Alasan Penting</option>
                            <option value="besar">Cuti Besar</option>
                        </select>
                    </div>

                    <!-- Filter Status Pegawai -->
                    <div>
                        <label for="filter_status_pegawai" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            <x-lucide-users class="w-4 h-4 inline mr-1" />
                            Status Pegawai
                        </label>
                        <select
                            id="filter_status_pegawai"
                            x-model="filters.status_pegawai"
                            @change="fetchData()"
                            class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm transition-colors duration-200"
                        >
                            <option value="all">Semua Status</option>
                            <option value="PNS">PNS</option>
                            <option value="PPPK">PPPK</option>
                        </select>
                    </div>

                    <!-- Reset Filter Button -->
                    <div class="flex items-end">
                        <button
                            type="button"
                            @click="clearFilters(); filters.jenis_surat = 'all'; filters.jenis_cuti = 'all'; filters.status_pegawai = 'all';"
                            class="inline-flex px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
                        >
                            <x-lucide-rotate-ccw class="w-4 h-4" />
                            Reset Filter
                        </button>
                    </div>
                </x-slot>

                <x-slot name="thead">
                    <th @click="sortBy('nomor_surat')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-2">
                            Nomor Surat
                            <x-lucide-arrow-up-down class="w-4 h-4 text-gray-400" />
                        </div>
                    </th>
                    <th @click="sortBy('jenis_surat')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-2">
                            Jenis Surat
                            <x-lucide-arrow-up-down class="w-4 h-4 text-gray-400" />
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pegawai
                    </th>
                    <th @click="sortBy('tanggal_surat')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-2">
                            Tanggal Surat
                            <x-lucide-arrow-up-down class="w-4 h-4 text-gray-400" />
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Perihal
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                        Aksi
                    </th>
                </x-slot>

                <x-slot name="tbody">
                    <tr class="hover:bg-gray-50/80 transition-colors duration-150 group border-b border-gray-100 last:border-0">
                        <!-- Nomor Surat -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col items-start gap-1">
                                <span class="font-mono text-sm font-semibold text-indigo-600 tracking-tight" x-text="item.nomor_surat"></span>

                                <!-- Sub-badge for Jenis Cuti -->
                                <template x-if="item.jenis_surat === 'cuti' && item.jenis_cuti">
                                    <span
                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium border ring-1 ring-inset capitalize"
                                        :class="{
                                            'bg-green-50 text-green-700 border-green-100 ring-green-600/20': item.jenis_cuti === 'tahunan',
                                            'bg-yellow-50 text-yellow-700 border-yellow-100 ring-yellow-600/20': item.jenis_cuti === 'sakit',
                                            'bg-pink-50 text-pink-700 border-pink-100 ring-pink-600/20': item.jenis_cuti === 'melahirkan',
                                            'bg-orange-50 text-orange-700 border-orange-100 ring-orange-600/20': item.jenis_cuti === 'alasan_penting',
                                            'bg-purple-50 text-purple-700 border-purple-100 ring-purple-600/20': item.jenis_cuti === 'besar',
                                        }"
                                        x-text="item.jenis_cuti.replace('_', ' ')"
                                    ></span>
                                </template>
                            </div>
                        </td>

                        <!-- Badge Jenis Surat & Cuti -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="{
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border': true,
                                'bg-blue-50 text-blue-700 border-blue-100 ring-1 ring-inset ring-blue-600/20': item.jenis_surat === 'tugas',
                                'bg-indigo-50 text-indigo-700 border-indigo-100 ring-1 ring-inset ring-indigo-600/20': item.jenis_surat === 'cuti',
                            }" x-text="item.jenis_surat === 'cuti' ? 'Surat Cuti' : 'Surat Tugas'">
                            </span>
                        </td>

                        <!-- Pegawai (dengan Avatar Inisial) -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900" x-text="item.pegawai_nama"></div>
                            </div>
                        </td>

                        <!-- Tanggal -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-sm text-gray-600">
                                <x-lucide-calendar class="w-4 h-4 text-gray-400 mr-2" />
                                <span class="font-medium" x-text="new Date(item.tanggal_surat).toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric'
                                })"></span>
                            </div>
                        </td>

                        <!-- Perihal -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 max-w-xs line-clamp-2 leading-relaxed" x-text="item.perihal"></div>
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                                <!-- View -->
                                <a :href="item.jenis_surat === 'cuti' ? `/surat-cuti/${item.id}` : `/surat-tugas/${item.id}`"
                                class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all duration-200"
                                title="Lihat Detail">
                                    <x-lucide-eye class="w-5 h-5" />
                                </a>
                            </div>
                        </td>
                    </tr>
                </x-slot>
            </x-data-table>
        </x-content-card>
    </div>
</x-app-layout>
