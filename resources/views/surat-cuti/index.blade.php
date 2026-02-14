<x-app-layout>
    <div class="mx-auto">
        <x-content-card
            icon="file-text"
            title="Daftar Surat Cuti"
            subtitle="Berikut adalah daftar semua surat cuti yang telah dibuat."
        >
            <x-slot name="action">
                <a href="{{ route('surat-cuti.create') }}" class="px-6 py-3 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all cursor-pointer flex items-center gap-2">
                    <x-lucide-plus class="w-4 h-4" />
                    Buat Surat Cuti
                </a>
            </x-slot>

            <x-data-table
                url="{{ route('surat-cuti.index') }}"
                :columns="[
                    'nomor_surat' => 'Nomor Surat',
                    'tanggal_surat' => 'Tanggal Surat',
                    'perihal' => 'Perihal',
                    'jenis_cuti' => 'Jenis Cuti',
                    'actions' => 'Aksi',
                ]"
            >
                <x-slot name="thead">
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nomor Surat</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Pegawai</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jenis Cuti</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </x-slot>

                <x-slot name="tbody">
                    <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="font-mono text-sm font-semibold text-indigo-600 tracking-tight" x-text="item.nomor_surat"></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600" x-text="item.pegawai_nama"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ring-1 ring-inset capitalize"
                                :class="{
                                    'bg-green-50 text-green-700 border-green-100 ring-green-600/20': item.jenis_cuti === 'tahunan',
                                    'bg-yellow-50 text-yellow-700 border-yellow-100 ring-yellow-600/20': item.jenis_cuti === 'sakit',
                                    'bg-pink-50 text-pink-700 border-pink-100 ring-pink-600/20': item.jenis_cuti === 'melahirkan',
                                    'bg-orange-50 text-orange-700 border-orange-100 ring-orange-600/20': item.jenis_cuti === 'alasan_penting',
                                    'bg-purple-50 text-purple-700 border-purple-100 ring-purple-600/20': item.jenis_cuti === 'besar',
                                }"
                                x-text="item.jenis_cuti.replace('_', ' ')"
                            ></span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="flex items-center gap-1.5">
                                <x-lucide-calendar class="w-3.5 h-3.5 text-gray-400" />
                                <span x-text="new Date(item.tanggal_surat).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})"></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <a :href="`/surat-cuti/${item.id}`"
                                   class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                                   title="Lihat Detail">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                                <a :href="`/surat-cuti/${item.id}/edit`"
                                   class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all duration-200"
                                   title="Edit">
                                    <x-lucide-pencil class="w-4 h-4" />
                                </a>
                            </div>
                        </td>
                    </tr>
                </x-slot>

            </x-data-table>
        </x-content-card>
    </div>
</x-app-layout>
