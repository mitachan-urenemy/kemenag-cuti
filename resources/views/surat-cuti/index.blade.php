<x-app-layout>
    <div class="mx-auto">
        <x-content-card
            icon="file-text"
            title="Daftar Surat Cuti"
            subtitle="Berikut adalah daftar semua surat cuti yang telah dibuat."
        >
            <x-slot name="action">
                <a href="{{ route('surat-cuti.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white uppercase transition-colors duration-200 bg-green-600 border border-transparent rounded-lg hover:bg-green-700">
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
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900" x-text="item.nomor_surat"></td>
                        <td class="px-6 py-4 text-sm text-gray-500" x-text="item.pegawai_nama"></td>
                        <td class="px-6 py-4 text-sm text-gray-500" x-text="item.jenis_cuti"></td>
                        <td class="px-6 py-4 text-sm text-gray-500" x-text="new Date(item.tanggal_surat).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})"></td>
                        <td class="px-6 py-4 text-sm font-medium text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a :href="`/surat-cuti/${item.id}`" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                                <a :href="`/surat-cuti/${item.id}/edit`" class="text-blue-600 hover:text-blue-900">Edit</a>
                            </div>
                        </td>
                    </tr>
                </x-slot>

            </x-data-table>
        </x-content-card>
    </div>
</x-app-layout>
