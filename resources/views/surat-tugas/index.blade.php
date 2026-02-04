<x-app-layout>
    <div class="mx-auto">
        <x-content-card
            icon="briefcase"
            title="Daftar Surat Tugas"
            subtitle="Berikut adalah daftar semua surat tugas yang telah dibuat."
        >
            <x-slot name="action">
                <a href="{{ route('surat-tugas.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white uppercase transition-colors duration-200 bg-green-600 border border-transparent rounded-lg hover:bg-green-700">
                    <x-lucide-plus class="w-4 h-4" />
                    Buat Surat Tugas
                </a>
            </x-slot>

            <x-data-table
                url="{{ route('surat-tugas.index') }}"
                :columns="[
                    'nomor_surat' => 'Nomor Surat',
                    'pegawai_names' => 'Pegawai Ditugaskan',
                    'tujuan_tugas' => 'Tujuan Tugas',
                    'tanggal_surat' => 'Tanggal Surat',
                    'actions' => 'Aksi',
                ]"
            >
                <x-slot name="thead">
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nomor Surat</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Pegawai Ditugaskan</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tujuan Tugas</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal Surat</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </x-slot>

                <x-slot name="tbody">
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900" x-text="item.nomor_surat"></td>
                        <td class="px-6 py-4 text-sm text-gray-500" x-text="item.pegawai_names"></td>
                        <td class="px-6 py-4 text-sm text-gray-500" x-text="item.tujuan_tugas"></td>
                        <td class="px-6 py-4 text-sm text-gray-500" x-text="new Date(item.tanggal_surat).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})"></td>
                        <td class="px-6 py-4 text-sm font-medium text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a :href="`/surat-tugas/${item.id}`" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                                <a :href="`/surat-tugas/${item.id}/edit`" class="text-blue-600 hover:text-blue-900">Edit</a>
                                <button
                                    @click="$dispatch('open-confirm-modal', {
                                        title: 'Hapus Surat Tugas?',
                                        message: 'Apakah Anda yakin ingin menghapus surat tugas ini? Tindakan ini tidak dapat dibatalkan.',
                                        action: `/surat-tugas/${item.id}`,
                                        method: 'DELETE'
                                    })"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                </x-slot>

            </x-data-table>
        </x-content-card>
    </div>
</x-app-layout>