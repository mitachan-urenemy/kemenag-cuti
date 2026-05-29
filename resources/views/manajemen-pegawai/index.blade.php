<x-app-layout>
    <div class="mx-auto">
        <x-content-card title="Daftar Pegawai" subtitle="Berikut adalah daftar semua pegawai yang terdaftar di sistem.">
            <x-slot name="action">
                <a href="{{ route('manajemen-pegawai.create') }}"
                    class="px-6 py-3 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all cursor-pointer flex items-center gap-2">
                    <x-lucide-plus class="w-4 h-4" />
                    Tambah Pegawai
                </a>
            </x-slot>

            <x-data-table url="{{ route('manajemen-pegawai.index') }}" :columns="[
        'nama_lengkap' => 'Pegawai',
        'username' => 'Akun',
        'jabatan' => 'Jabatan / Golongan',
        'unit_kerja' => 'Unit Kerja',
        'actions' => 'Aksi',
    ]">
                <x-slot name="thead">
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Pegawai
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Akun</th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jabatan /
                        Golongan</th>
                    <th scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Unit
                        Kerja</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </x-slot>

                <x-slot name="tbody">
                    <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-900" x-text="item.nama_lengkap"></span>
                                    <span class="text-xs text-gray-500"
                                        x-text="item.nip ? 'NIP. ' + item.nip : '-'"></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-900" x-text="item.username"></span>
                                    <span
                                        class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium border"
                                        :class="item.user?.status ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200'"
                                        x-text="item.user?.status ? 'Aktif' : 'Nonaktif'"
                                    ></span>
                                </div>
                                <span class="text-xs text-gray-500 capitalize" x-text="item.user?.role || '-'"></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900" x-text="item.jabatan || '-'"></span>
                                <span class="text-xs text-gray-500" x-text="item.pangkat_golongan || '-'"></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-900" x-text="item.unit_kerja || '-'"></span>
                                <span class="text-xs text-gray-500" x-text="item.status_kepegawaian || '-'"></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="$dispatch('open-confirm-modal', {
                                        title: 'Ubah Status Aktivasi Akun?',
                                        message: 'Apakah Anda yakin ingin mengubah status aktivasi akun pegawai ini?',
                                        action: `/manajemen-pegawai/status/${item.id}`,
                                        method: 'PUT',
                                        type: 'success',
                                        confirmText: 'Ya, Ubah Status'
                                    })"
                                    class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200"
                                    title="Ubah Status Aktifasi Akun">
                                    <x-lucide-check class="w-4 h-4" />
                                </button>
                                <a :href="`/manajemen-pegawai/${item.id}/edit`"
                                    class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all duration-200"
                                    title="Edit Data">
                                    <x-lucide-pencil class="w-4 h-4" />
                                </a>
                                <button @click="$dispatch('open-confirm-modal', {
                                        title: 'Hapus Pegawai?',
                                        message: 'Apakah Anda yakin ingin menghapus data pegawai ini? Tindakan ini tidak dapat dibatalkan.',
                                        action: `/manajemen-pegawai/${item.id}`,
                                        method: 'DELETE'
                                    })"
                                    class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                    title="Hapus">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </x-slot>

            </x-data-table>
        </x-content-card>
    </div>
</x-app-layout>
